<?php

namespace App\Livewire\Dashboard;

use App\Domain\Withdrawal\WithdrawalRules;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\CustomSetting;
use App\Models\WithdrawalCurrency;
use App\Services\NotificationService;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\DB;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Layout;
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
            'address' => 'string|max:120',
        ];
    }

    protected function messages() { 
        return  [
            'amount.min' => "Amount must be at least $" . $this->minimumWithdrawalAmount,
            'selectedWallet.required' => "Please choose a currency.",
            'address' => 'Enter correct address to proceed'
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

        $withdrawal = DB::transaction(function () {
            $q = \App\Models\Withdrawal::create([
                'user_id' => auth()->id(),
                'amount' => $this->amount,
                'address' => $this->address,
                'asset' => 'main',
                'withdrawal_currency_id' => $this->selectedWallet->id,
                'withdrawal_network_id' => $this->network,
                'meta' => [
                    'total_to_debit' => $this->amount,
                    'fee' => $this->fee
                ],
                'reference' => generate_withdrawal_reference()
            ]);

            return $q;
        });

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

        $this->amount = str_replace(',', '', $this->amount);
        $this->validate();

        $this->showConfirm = true;
        $this->type = 'danger';
        $this->title = 'Confirm Withdrawal';
        $this->text = 'You are about to withdraw funds from your account. Please review the details carefully before proceeding.';
        $this->warning = 'Ensure the wallet address and network are correct. Transactions cannot be reversed once processed.';
        $this->confirmText = 'Yes, Withdraw';
        $this->icon = '💸';
        $this->action = 'makeWithdrawal';
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
