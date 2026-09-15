<?php

namespace App\Livewire\Dashboard;

use App\Domain\Withdrawal\WithdrawalAddressValidator;
use App\Domain\Withdrawal\WithdrawalRules;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\CustomSetting;
use App\Models\WithdrawalCurrency;
use App\Models\WithdrawalNetwork;
use App\Services\NotificationService;
use App\Services\Wallet\WalletService;
use App\Services\WithdrawalService;
use Illuminate\Support\Facades\DB;
use Laravel\Fortify\TwoFactorAuthenticationProvider;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('components.layouts.app')]
class Withdrawal extends Component {
    public $currencies = [];
    public $selectedWallet;
    public $network;
    public $amount;
    public $address;
    public $networks = [];
    public $perPage = 10;

    // ////////////////////////
    public $showConfirm = false;
    public $title;
    public $text;
    public $warning;
    public $type = 'danger';
    public $confirmText = 'Confirm';
    public $icon = '⚠️';
    public $action;

    // //////////////// Withdrawal 2FA (Google Authenticator) ////////////////
    // A withdrawal must never leave makeWithdrawal() without this having
    // been set true by verifyTwoFactorAndWithdraw() first. #[Locked] stops
    // the client from setting it directly — Livewire will reject any
    // request that tries to change a locked property from the frontend, so
    // calling wire:click="makeWithdrawal" straight from devtools without
    // going through verification still lands on a false flag.
    #[Locked]
    public bool $twoFactorVerified = false;

    public bool $showTwoFactorModal = false;
    public string $twoFactorCode = '';

    #[Computed]
    public function minimumWithdrawalAmount() {
        return CustomSetting::get('minimum_withdrawal') ?? 20;
    }

    public function getFeeProperty() {
        $fee = CustomSetting::get('withdrawal_fee');
        return ($this->amount ?? 0) * ($fee * 0.01);
    }

    public function getNetAmountProperty() {
        return ($this->amount ?? 0) - $this->fee;
    }


    protected function rules() {
        return [
            'amount' => 'required|numeric|min:' . $this->minimumWithdrawalAmount,
            'selectedWallet' => 'required',
            'address' => ['required', 'string', 'max:120', function ($attribute, $value, $fail) {
                // Catches "picked ETH, pasted a BTC address" before it becomes
                // an irreversible on-chain mistake. Network (e.g. TRC20 vs
                // ERC20) takes priority over the bare currency code, since for
                // multi-chain tokens like USDT the network is what actually
                // determines the address format — see WithdrawalAddressValidator.
                $currencyCode = $this->selectedWallet?->code ?? null;
                $networkName = $this->network ? WithdrawalNetwork::find($this->network)?->name : null;

                if (! WithdrawalAddressValidator::isValid((string) $value, $currencyCode, $networkName)) {
                    $label = WithdrawalAddressValidator::expectedLabel($currencyCode, $networkName);
                    $fail("This doesn't look like a valid {$label} address. Double-check it matches the currency/network you selected.");
                }
            }],
        ];
    }

    protected function messages() {
        return  [
            'amount.min' => "Amount must be at least $" . $this->minimumWithdrawalAmount,
            'selectedWallet.required' => "Please choose a currency.",
            'address.required' => 'Enter your wallet address to proceed.',
        ];
    }


    public function mount() {
        $this->currencies = WithdrawalCurrency::with('networks')->where('is_enabled', true)->get();
    }

    public function selectWallet($currencyId) {
        $currency = WithdrawalCurrency::with('networks')->find($currencyId);
        $this->selectedWallet = $currency;
        $this->networks = $currency?->networks?->toArray() ?? [];
        $this->network = $currency?->networks?->first()?->id ?? null;

        // dd($this->networks);
    }


    public function makeWithdrawal() {
        if (! $this->twoFactorVerified) {
            $this->dispatch('error', message: 'Two-factor verification is required before withdrawing.');
            return;
        }

        // Consume it immediately so this flag can never cover a second
        // withdrawal — every attempt needs its own fresh code.
        $this->twoFactorVerified = false;

        if(auth()->user()->suspended_at) {
            $this->dispatch('error', message: 'Your account has been suspended.');
            return;
        }
        $this->amount = str_replace(',', '', $this->amount);
        $this->validate();

        if ($this->amount > auth()->user()->main_balance) {
            $this->addError('amount', 'Insufficient balance to cover withdrawal.');
            return;
        }

        // try {
        //     WithdrawalRules::canCreate(auth()->user(), $this->amount);
        // } catch (\DomainException $e) {
        //     $this->addError('amount', $e->getMessage());
        //     return;
        // }

        // Creation, and the user/admin notification emails that go with it,
        // live in WithdrawalService so both this form and any other entry
        // point (e.g. a future API) behave identically. The service
        // independently re-checks the address against the chosen
        // currency/network — the same rule the field validator above
        // already enforces, kept here too so this can never be bypassed by
        // skipping client-side validation.
        try {
            $withdrawal = WithdrawalService::create(auth()->user(), [
                'amount' => $this->amount,
                'address' => $this->address,
                'withdrawal_currency_id' => $this->selectedWallet->id,
                'withdrawal_network_id' => $this->network,
                'fee' => $this->fee,
            ]);
        } catch (\DomainException $e) {
            $this->addError('address', $e->getMessage());
            return;
        }

        return redirect()->route('withdrawal.page', ['withdrawal' => $withdrawal->id]);
    }

    public function cancelConfirm() {
        $this->showConfirm = false;
    }

    public function prepareWithdrawal() {
        if(auth()->user()->kyc_status !== 'approved') {
            $this->dispatch('error', message: 'You cannot withdraw until your KYC is approved.');
            return;
        }

        if (! auth()->user()->hasEnabledTwoFactorAuthentication()) {
            $this->dispatch('error', message: 'Please enable Two-Factor Authentication in Settings before making a withdrawal.');
            return;
        }

        $this->amount = str_replace(',', '', $this->amount);
        $this->validate();

        $this->showConfirm = true;
        $this->type = 'danger';
        $this->title = 'Confirm Withdrawal';
        $this->text = 'You are about to withdraw funds from your account. Please review the details carefully before proceeding.';
        $this->warning = 'Ensure the wallet address and network are correct. Transactions cannot be reversed once processed.';
        $this->confirmText = 'Yes, Withdraw';
        $this->icon = '💸';
        // Routed through the 2FA step rather than straight to makeWithdrawal
        // — see openTwoFactorStep()/verifyTwoFactorAndWithdraw() below.
        $this->action = 'openTwoFactorStep';
    }

    /**
     * Called by the generic confirm modal's "Yes, Withdraw" button. Swaps
     * that modal for the Google Authenticator code prompt — the actual
     * withdrawal doesn't happen until verifyTwoFactorAndWithdraw() succeeds.
     */
    public function openTwoFactorStep() {
        $this->showConfirm = false;
        $this->showTwoFactorModal = true;
        $this->resetErrorBag();
    }

    public function cancelTwoFactor() {
        $this->reset('showTwoFactorModal', 'twoFactorCode');
        $this->resetErrorBag();
    }

    /**
     * Verify the 6-digit code from the user's authenticator app using the
     * same Fortify provider the login challenge and Settings > Two-Factor
     * confirmation already use, then let the withdrawal proceed.
     */
    public function verifyTwoFactorAndWithdraw(TwoFactorAuthenticationProvider $provider) {
        $this->validate(['twoFactorCode' => 'required|string|size:6'], [], ['twoFactorCode' => 'code']);

        $user = auth()->user();

        if (! $user->hasEnabledTwoFactorAuthentication()) {
            // Covers the edge case where 2FA got disabled in another tab
            // between opening the withdrawal form and this step.
            $this->cancelTwoFactor();
            $this->dispatch('error', message: 'Two-Factor Authentication is no longer enabled on your account.');
            return;
        }

        $valid = $provider->verify(decrypt($user->two_factor_secret), $this->twoFactorCode);

        if (! $valid) {
            $this->addError('twoFactorCode', 'That code is invalid or has expired. Please try again.');
            return;
        }

        $this->reset('showTwoFactorModal', 'twoFactorCode');
        $this->twoFactorVerified = true;

        $this->makeWithdrawal();
    }

    public function confirmAction() {
        $action = $this->action;

        if (method_exists($this, $action)) {
            $this->$action();
        }

        $this->showConfirm = false;
    }

    public function deleteWithdrawal() {

    }

    public function processConvert($from, $amount) {
        $min_conversion = CustomSetting::get('minimum_conversion') ?? 10;
        $user = auth()->user();
        $amount = (float) $amount;

        if ($amount <= 0) {
            $this->dispatch('error', message: 'Invalid amount');
            return;
        }

        if ($amount < $min_conversion) {
            $min_conversion = number_format($min_conversion, 2);
            $this->dispatch('error', message: "Minimum conversion amount is $$min_conversion");
            return;
        }

        return DB::transaction(function () use ($user, $amount, $from) {

            if ($from === 'profit') {
                if ($user->profit_balance < $amount) {
                    $this->dispatch('error', message: 'Insufficient profit balance');
                    return;
                }

                WalletService::debit(
                    $user,
                    $amount,
                    LedgerReference::PROFITTRANSFER,
                    null,
                    'profit transfer to main balance',
                    LedgerAsset::PROFIT
                );

                WalletService::credit(
                    $user,
                    $amount,
                    LedgerReference::PROFITTRANSFER,
                    null,
                    'profit transfer from profit balance',
                    LedgerAsset::MAIN
                );

                $this->dispatch('success', message: "$$amount convertion was successful");
                return true;

            } else {
                if ($user->referral_balance < $amount) {
                    $this->dispatch('error', message: 'Insufficient referral balance');
                    return;
                }

                WalletService::debit(
                    $user,
                    $amount,
                    LedgerReference::REFERRALBONUSTRANSFER,
                    null,
                    'referral bonus transfer to main balance',
                    LedgerAsset::REFERRALBONUS
                );

                WalletService::credit(
                    $user,
                    $amount,
                    LedgerReference::REFERRALBONUSTRANSFER,
                    null,
                    'referral bonus transfer from referral bonus balance',
                    LedgerAsset::MAIN
                );
                $this->dispatch('success', message: "$$amount convertion was successful");
                return true;
            }

            // NotificationService::createForUser($user, [
            //     'title' => 'Conversion Successful',
            //     'message' => "You converted $$amount successfully.",
            // ]);

            // return true;
        });
    }

    public function render() {
        $withdrawals = \App\Models\Withdrawal::where('user_id', auth()->id())
            ->latest()
            ->latest()
            ->paginate($this->perPage);
        return view('livewire.dashboard.withdrawal', [
            'withdrawals' => $withdrawals
        ]);
    }
}
