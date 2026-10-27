<?php

namespace App\Http\Controllers\Api\V1;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Http\Controllers\Controller;
use App\Http\Resources\InvestmentResource;
use App\Models\Bot;
use App\Models\BotInvestment;
use App\Models\BotLicense;
use App\Models\User;
use App\Services\DepositService;
use App\Services\Promo\RoiPromoService;
use App\Services\ReferralBonusService;
use App\Services\Wallet\WalletService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class BotController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();

        $licenses = BotLicense::where('user_id', $user->id)->get()->keyBy('bot_id');

        $bots = Bot::withSum('profitCycles as total_profit', 'profit_amount')
            ->where('is_active', true)
            ->get()
            ->map(function ($bot) use ($licenses) {
                $license = $licenses->get($bot->id);

                return [
                    'id' => $bot->id,
                    'name' => $bot->name,
                    'description' => $bot->description,
                    'price' => (float) $bot->price,
                    'min_amount' => (float) $bot->min_amount,
                    'max_amount' => (float) $bot->max_amount,
                    'lock_days' => (int) $bot->lock_days,
                    'license_duration_days' => (int) $bot->license_duration_days,
                    'daily_return_percent' => (float) $bot->daily_return_percent,
                    'payout_interval_hours' => (int) $bot->payout_interval_hours,
                    'total_profit' => (float) $bot->total_profit,
                    'license' => $license ? [
                        'id' => $license->id,
                        'active' => $license->expires_at?->isFuture() ?? false,
                        'expires_at' => $license->expires_at?->toIso8601String(),
                    ] : null,
                ];
            });

        return response()->json([
            'bots' => $bots,
            'balances' => [
                'main' => (float) $user->main_balance,
                'deposit' => (float) $user->deposit_balance,
                'deposit_with_bonus' => (float) $user->deposit_balance + (float) $user->deposit_bonus_balance,
            ],
        ]);
    }

    public function buyLicense(Request $request, $botId)
    {
        $data = $request->validate([
            'asset' => ['required', 'in:main,deposit'],
        ]);

        if ($request->user()->suspended_at) {
            return response()->json(['message' => 'Your account has been suspended.'], 403);
        }

        $bot = Bot::where('is_active', true)->findOrFail($botId);
        $asset = $data['asset'] === 'deposit' ? LedgerAsset::DEPOSIT : LedgerAsset::MAIN;

        $license = DB::transaction(function () use ($request, $bot, $asset, $data) {
            $user = User::lockForUpdate()->find($request->user()->id);

            $active = BotLicense::where('user_id', $user->id)
                ->where('bot_id', $bot->id)
                ->where('expires_at', '>', now())
                ->lockForUpdate()
                ->first();

            if ($active) {
                throw ValidationException::withMessages(['bot' => 'You already have an active license for this bot.']);
            }

            $this->debit($user, (float) $bot->price, LedgerReference::LICENSE_PURCHASE, $bot->id, null, $asset);

            return BotLicense::updateOrCreate(
                ['user_id' => $user->id, 'bot_id' => $bot->id],
                [
                    'status' => 'active',
                    'starts_at' => now(),
                    'expires_at' => now()->addDays($bot->license_duration_days),
                    'meta' => ['price' => $bot->price, 'asset_used' => $data['asset']],
                ]
            );
        });

        return response()->json([
            'message' => "{$bot->name} license activated.",
            'license' => [
                'id' => $license->id,
                'active' => true,
                'expires_at' => $license->expires_at->toIso8601String(),
            ],
        ], 201);
    }

    public function investments(Request $request)
    {
        $investments = BotInvestment::with('bot')
            ->where('user_id', $request->user()->id)
            ->when($request->status, fn ($q) => $q->where('status', $request->status))
            ->latest()
            ->paginate(20);

        return InvestmentResource::collection($investments);
    }

    public function showInvestment(Request $request, $uuid)
    {
        $investment = BotInvestment::with(['bot', 'cycles' => fn ($q) => $q->latest('cycle_at')->limit(30)])
            ->where('user_id', $request->user()->id)
            ->where('uuid', $uuid)
            ->firstOrFail();

        return new InvestmentResource($investment);
    }

    public function storeInvestment(Request $request)
    {
        if ($request->user()->suspended_at) {
            return response()->json(['message' => 'Your account has been suspended.'], 403);
        }

        $data = $request->validate([
            'license_id' => ['required', 'integer'],
            'amount' => ['required', 'numeric'],
            'asset' => ['required', 'in:main,deposit'],
        ]);

        $license = BotLicense::with('bot')
            ->where('user_id', $request->user()->id)
            ->where('expires_at', '>', now())
            ->find($data['license_id']);

        if (! $license) {
            throw ValidationException::withMessages(['license_id' => 'This license is not active. Please renew it first.']);
        }

        $bot = $license->bot;
        $amount = (float) $data['amount'];

        if ($amount < $bot->min_amount) {
            throw ValidationException::withMessages([
                'amount' => 'Minimum investment for this bot is $' . number_format($bot->min_amount, 2),
            ]);
        }

        $investment = DB::transaction(function () use ($request, $license, $bot, $amount, $data) {
            $user = User::lockForUpdate()->find($request->user()->id);
            $fundedAmount = $amount;

            if ($data['asset'] === 'deposit') {
                try {
                    $breakdown = DepositService::debitForInvestment($user, $amount);
                } catch (\Throwable $e) {
                    throw ValidationException::withMessages(['amount' => $e->getMessage()]);
                }
                $fundedAmount = $breakdown['from_deposit'];
            } else {
                $this->debit($user, $amount, LedgerReference::BOT_INVESTMENT, $license->id, null, LedgerAsset::MAIN);
            }

            $investment = BotInvestment::create([
                'user_id' => $user->id,
                'bot_id' => $bot->id,
                'bot_license_id' => $license->id,
                'amount' => $amount,
                'capital' => $amount,
                'referral_eligible_amount' => $fundedAmount,
                'started_at' => now(),
                'locked_until' => now()->addDays($bot->lock_days),
                'matures_at' => now()->addDays($bot->lock_days),
                'next_cycle_at' => now()->addHours($bot->payout_interval_hours),
                'meta' => [
                    'daily_return_percent' => $bot->daily_return_percent,
                    'payout_interval_hours' => $bot->payout_interval_hours,
                ],
            ]);

            try {
                RoiPromoService::tag($investment);
            } catch (\Throwable $e) {
                report($e);
            }

            WalletService::credit($user, $amount, LedgerReference::BOT_INVESTMENT, $investment->id,
                'locked investment', LedgerAsset::LOCKEDBALANCE);

            ReferralBonusService::distribute($user, $investment);

            return $investment;
        });

        return (new InvestmentResource($investment->fresh('bot')))
            ->additional(['message' => 'Investment deployed successfully!'])
            ->response()
            ->setStatusCode(201);
    }

    // WalletService throws a Filament Halt on low balance, turn it into a normal validation error
    private function debit(User $user, float $amount, LedgerReference $ref, ?int $refId, ?string $desc, LedgerAsset $asset)
    {
        if ($user->getBalance($asset) < $amount) {
            throw ValidationException::withMessages(['amount' => 'Insufficient balance.']);
        }

        return WalletService::debit($user, $amount, $ref, $refId, $desc, $asset);
    }
}
