<?php

namespace App\Livewire\Dashboard;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\BotInvestment;
use App\Models\BotLicense;
use App\Services\Wallet\WalletService;
use Livewire\Component;

class Investment extends Component
{
    public $amount;
    public $bot;
    public $license;
    public $showModal = false;
    public $selectedLicense;
    public $asset = 'main';

    public function mount()
    {
        $this->licenses = BotLicense::with('bot')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
    }

    public function rules() {
        return [
            'amount' => 'required'
        ];
    }

    public function openModal($licenseId)
    {
        $this->selectedLicense = BotLicense::with('bot')->findOrFail($licenseId);
        $this->amount = null;
        $this->showModal = true;
    }

    public function render()
    {
        $licenses = BotLicense::with('bot')
            ->where('user_id', auth()->id())
            ->latest()
            ->get();
        $investments = BotInvestment::where([
            'user_id' => auth()->id()
        ])->latest()
            ->paginate();

        return view('livewire.dashboard.investment', [
            'licenses' => $licenses,
            'investments' => $investments
        ]);
    }

    public function createInvestment() {
        if(!$this->selectedLicense) return;

        $this->validate([
            'amount' => [
                'required',
                'numeric',
                'min:' . $this->selectedLicense->bot->min_amount,
                'max:' . $this->selectedLicense->bot->max_amount,
            ],
        ]);

        try {

        // dd($this->asset);

            $asset = $this->asset === 'deposit' ? LedgerAsset::DEPOSIT : LedgerAsset::MAIN;


            WalletService::debit(auth()->user(), $this->amount, LedgerReference::BOT_INVESTMENT, $this->selectedLicense->id, null, $asset);    

            BotInvestment::create([
                'user_id' => auth()->id(),
                'bot_id' => $this->selectedLicense->bot->id,
                'bot_license_id' => $this->selectedLicense->id,
                'amount' => $this->amount,
                'capital' => $this->amount,
                'started_at' => now(),
                'locked_until' => now()->addDays($this->selectedLicense->bot->lock_days),
                'matures_at' => now()->addDays($this->selectedLicense->bot->lock_days),
                'next_cycle_at' => now()->addHours($this->selectedLicense->bot->payout_interval_hours),
                'meta' => [
                    'daily_return_percent' => $this->selectedLicense->bot->daily_return_percent,
                    'payout_interval_hours' => $this->selectedLicense->bot->payout_interval_hours,
                ]
            ]);

            $this->showModal = false;
            $this->dispatch('toast', payload: [
                'message' => 'Investment deployed successfully!'
            ]);
        } catch(\Exception $e) {
            $this->addError('amount', $e->getMessage());
        }

        
    }
}
