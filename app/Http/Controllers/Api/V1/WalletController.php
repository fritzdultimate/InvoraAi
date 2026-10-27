<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\DepositStatus;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Http\Controllers\Controller;
use App\Http\Resources\DepositResource;
use App\Http\Resources\WithdrawalResource;
use App\Models\CustomSetting;
use App\Models\Deposit;
use App\Models\User;
use App\Models\Withdrawal;
use App\Models\WithdrawalCurrency;
use App\Models\WithdrawalNetwork;
use App\Services\NowPaymentsService;
use App\Services\Wallet\WalletService;
use App\Services\WithdrawalService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Validation\ValidationException;
use Laravel\Fortify\Contracts\TwoFactorAuthenticationProvider;

class WalletController extends Controller
{
    const MIN_DEPOSIT = 50;

    public function depositOptions(NowPaymentsService $np)
    {
        $currencies = Cache::remember('api_deposit_currencies', now()->addMinutes(30), function () use ($np) {
            return collect($np->getCurrencies())->map(fn ($c) => [
                'currency' => $c['currency'],
                'label' => $c['label'],
                'networks' => collect($c['networks'])->map(fn ($n) => [
                    'label' => $n['label'],
                    'raw' => $n['raw'],
                ])->values(),
            ])->values();
        });

        return response()->json([
            'currencies' => $currencies,
            'min_amount' => self::MIN_DEPOSIT,
        ]);
    }

    public function storeDeposit(Request $request, NowPaymentsService $np)
    {
        $request->merge(['amount' => str_replace(',', '', (string) $request->amount)]);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:' . self::MIN_DEPOSIT],
            'currency' => ['required', 'string', 'max:30'],
        ], [
            'amount.min' => 'Amount must be at least $' . self::MIN_DEPOSIT . '.',
        ]);

        $allowed = collect($np->getCurrencies())->pluck('networks')->flatten(1)->pluck('raw')->all();

        if (! in_array(strtoupper($data['currency']), $allowed, true)) {
            throw ValidationException::withMessages(['currency' => 'Please choose a valid currency.']);
        }

        try {
            $deposit = DB::transaction(function () use ($request, $data) {
                $deposit = Deposit::create([
                    'user_id' => $request->user()->id,
                    'currency' => strtolower($data['currency']),
                    'amount' => $data['amount'],
                    'status' => DepositStatus::WAITING,
                    'reference' => generate_deposit_reference(),
                ]);

                $invoice = NowPaymentsService::createInvoice($deposit);

                $deposit->nowpayments_invoice_id = $invoice['payment_id'] ?? null;
                $deposit->meta = $invoice;
                $deposit->address = $invoice['pay_address'];
                $deposit->save();

                return $deposit;
            });
        } catch (\Throwable $e) {
            Log::error('API deposit failed: ' . $e->getMessage(), ['user' => $request->user()->id]);

            return response()->json([
                'message' => 'We could not create a deposit address right now. Please try again.',
            ], 502);
        }

        return new DepositResource($deposit);
    }

    public function deposits(Request $request)
    {
        $deposits = Deposit::where('user_id', $request->user()->id)->latest()->paginate(20);

        return DepositResource::collection($deposits);
    }

    public function showDeposit(Request $request, $id)
    {
        $deposit = Deposit::where('user_id', $request->user()->id)->findOrFail($id);

        if ($deposit->status === DepositStatus::WAITING && $deposit->created_at->addMinutes(20)->isPast()) {
            $deposit->update(['status' => DepositStatus::EXPIRED]);
        }

        return new DepositResource($deposit);
    }

    public function withdrawalOptions(Request $request)
    {
        $user = $request->user();

        $currencies = WithdrawalCurrency::with('networks')
            ->where('is_enabled', true)
            ->get()
            ->map(fn ($c) => [
                'id' => $c->id,
                'code' => $c->code,
                'name' => $c->name,
                'networks' => $c->networks->map(fn ($n) => ['id' => $n->id, 'name' => $n->name])->values(),
            ]);

        return response()->json([
            'currencies' => $currencies,
            'fee_percent' => $this->feePercent(),
            'min_amount' => (float) (CustomSetting::get('minimum_withdrawal') ?? 20),
            'available_balance' => (float) $user->main_balance,
            'kyc_approved' => $user->kyc_status === 'approved',
            'two_factor_enabled' => $user->hasEnabledTwoFactorAuthentication(),
            'min_conversion' => (float) (CustomSetting::get('minimum_conversion') ?? 10),
        ]);
    }

    public function storeWithdrawal(Request $request, TwoFactorAuthenticationProvider $provider)
    {
        $user = $request->user();

        if ($user->suspended_at) {
            return response()->json(['message' => 'Your account has been suspended.'], 403);
        }

        if ($user->kyc_status !== 'approved') {
            return response()->json([
                'message' => 'You cannot withdraw until your KYC is approved.',
                'code' => 'kyc_required',
            ], 403);
        }

        if (! $user->hasEnabledTwoFactorAuthentication()) {
            return response()->json([
                'message' => 'Please enable Two-Factor Authentication before making a withdrawal.',
                'code' => 'two_factor_required',
            ], 403);
        }

        $minimum = (float) (CustomSetting::get('minimum_withdrawal') ?? 20);
        $request->merge(['amount' => str_replace(',', '', (string) $request->amount)]);

        $data = $request->validate([
            'amount' => ['required', 'numeric', 'min:' . $minimum],
            'currency_id' => ['required', 'exists:withdrawal_currencies,id'],
            'network_id' => ['nullable', 'exists:withdrawal_networks,id'],
            'address' => ['required', 'string', 'max:120'],
            'code' => ['required', 'string', 'size:6'],
        ], [
            'amount.min' => 'Amount must be at least $' . $minimum,
            'address.required' => 'Enter your wallet address to proceed.',
        ]);

        if (! $provider->verify(decrypt($user->two_factor_secret), $data['code'])) {
            throw ValidationException::withMessages([
                'code' => 'That code is invalid or has expired. Please try again.',
            ]);
        }

        if (! empty($data['network_id'])) {
            $belongs = WithdrawalNetwork::where('id', $data['network_id'])
                ->where('withdrawal_currency_id', $data['currency_id'])
                ->exists();

            if (! $belongs) {
                throw ValidationException::withMessages(['network_id' => 'Invalid network for this currency.']);
            }
        }

        if ($data['amount'] > $user->main_balance) {
            throw ValidationException::withMessages(['amount' => 'Insufficient balance to cover withdrawal.']);
        }

        try {
            $withdrawal = WithdrawalService::create($user, [
                'amount' => $data['amount'],
                'address' => $data['address'],
                'withdrawal_currency_id' => $data['currency_id'],
                'withdrawal_network_id' => $data['network_id'] ?? null,
                'fee' => $data['amount'] * $this->feePercent() / 100,
            ]);
        } catch (\DomainException $e) {
            throw ValidationException::withMessages(['address' => $e->getMessage()]);
        }

        return new WithdrawalResource($withdrawal->load('currency', 'network'));
    }

    public function withdrawals(Request $request)
    {
        $withdrawals = Withdrawal::with('currency', 'network')
            ->where('user_id', $request->user()->id)
            ->latest()
            ->paginate(20);

        return WithdrawalResource::collection($withdrawals);
    }

    public function convert(Request $request)
    {
        $minimum = (float) (CustomSetting::get('minimum_conversion') ?? 10);

        $data = $request->validate([
            'from' => ['required', 'in:profit,referral_bonus'],
            'amount' => ['required', 'numeric', 'min:' . $minimum],
        ], [
            'amount.min' => 'Minimum conversion amount is $' . number_format($minimum, 2),
        ]);

        $amount = (float) $data['amount'];

        DB::transaction(function () use ($request, $data, $amount) {
            $user = User::lockForUpdate()->find($request->user()->id);

            if ($data['from'] === 'profit') {
                if ($user->profit_balance < $amount) {
                    throw ValidationException::withMessages(['amount' => 'Insufficient profit balance']);
                }

                WalletService::debit($user, $amount, LedgerReference::PROFITTRANSFER, null,
                    'profit transfer to main balance', LedgerAsset::PROFIT);
                WalletService::credit($user, $amount, LedgerReference::PROFITTRANSFER, null,
                    'profit transfer from profit balance', LedgerAsset::MAIN);
            } else {
                if ($user->referral_balance < $amount) {
                    throw ValidationException::withMessages(['amount' => 'Insufficient referral balance']);
                }

                WalletService::debit($user, $amount, LedgerReference::REFERRALBONUSTRANSFER, null,
                    'referral bonus transfer to main balance', LedgerAsset::REFERRALBONUS);
                WalletService::credit($user, $amount, LedgerReference::REFERRALBONUSTRANSFER, null,
                    'referral bonus transfer from referral bonus balance', LedgerAsset::MAIN);
            }
        });

        return response()->json([
            'message' => '$' . number_format($amount, 2) . ' moved to your main balance.',
        ]);
    }

    private function feePercent(): float
    {
        $fee = str_replace([',', '%', ' '], '', (string) CustomSetting::get('withdrawal_fee'));

        return is_numeric($fee) ? (float) $fee : 0.0;
    }
}
