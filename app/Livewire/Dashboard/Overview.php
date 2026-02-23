<?php

namespace App\Livewire\Dashboard;

use App\Models\BotInvestment;
use App\Models\BotLicense;
use App\Models\WalletLedger;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class  Overview extends Component {
    public $search = '';
    public $type = '';
    public $perPage = 10;

    public $deposit_balance = 0;
    public $total_executed_trades = 0;
    public $bot_status = '-';
    public $main_balance = 0;
    public $referral_balance = 0;
    public $has_active_license = 0;
    public $license_expires_at;

    protected $queryString = ['search', 'type'];

    public function mount() {
        $this->deposit_balance = optional(
            auth()->user()->ledgers()->where('asset', 'deposit')->latest()->first()
        )->balance_after ?? 0;

        $this->total_executed_trades = BotInvestment::where('user_id', auth()->id())->count();

        $this->bot_status = BotLicense::where('user_id', auth()->id())
            ->whereFuture('expires_at')
            ->exists()
            ? 'active'
            : 'Not Active';

        $this->main_balance = auth()->user()->balance;
        $this->referral_balance = 0;
        $this->has_active_license = auth()->user()->hasActiveLicense();
        $this->license_expires_at = optional(
            auth()->user()->botLicenses()->latest()->first()
        )->expires_at;
    }

    /**
     * Mount the component.
     */
    public function render(): \Illuminate\View\View {
        $transactions = WalletLedger::where('user_id', auth()->id())
            ->when($this->search, fn ($q) =>
                $q->where('reference', 'like', "%{$this->search}%")
            )
            ->when($this->type, fn ($q) =>
                $q->where('type', $this->type)
            )
            ->latest()
            ->paginate($this->perPage);

        return view('livewire.dashboard.overview', [
            'transactions' => $transactions
        ]);
    }
}