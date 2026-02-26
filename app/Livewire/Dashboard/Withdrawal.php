<?php

namespace App\Livewire\Dashboard;

use App\Domain\Withdrawal\WithdrawalRules;
use App\Models\WithdrawalCurrency;
use Illuminate\Support\Facades\DB;
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


    protected $rules = [
        'amount' => 'required|numeric|min:50',
        'selectedWallet' => 'required',
        'address' => 'string|max:120',
    ];

    protected $messages = [
        'amount.min' => "Amount must be at least $50.",
        'selectedWallet.required' => "Please choose a currency.",
        'address' => 'Enter correct address to proceed'
    ];


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

        if ($this->amount > auth()->user()->balance) {
            $this->addError('amount', 'Insufficient balance to cover withdrawal.');
            return;
        }

        try {
            WithdrawalRules::canCreate(auth()->user(), $this->amount);
        } catch (\DomainException $e) {
            $this->addError('amount', $e->getMessage());
            return;
        }

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
                ],
                'reference' => generate_withdrawal_reference()
            ]);

            return $q;
        });

        return redirect()->route('withdrawal.page', ['withdrawal' => $withdrawal->id]);
    }

    public function deleteWithdrawal() {

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
