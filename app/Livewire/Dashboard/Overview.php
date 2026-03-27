<?php

namespace App\Livewire\Dashboard;

use App\Models\BotInvestment;
use App\Models\BotLicense;
use App\Models\BotProfitCycle;
use App\Models\Deposit;
use App\Models\WalletLedger;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
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
    public $profit_balance = 0;
    public $locked_balance = 0;
    public $deposit_bonus = 0;
    public $has_active_license = 0;
    public $license_expires_at;

    protected $queryString = ['search', 'type'];

    public $chartData = [];

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

        $this->main_balance = auth()->user()->main_balance;
        $this->locked_balance = auth()->user()->locked_balance;
        $this->referral_balance = auth()->user()->referral_balance;
        $this->has_active_license = auth()->user()->hasActiveLicense();
        $this->license_expires_at = optional(
            auth()->user()->botLicenses()->latest()->first()
        )->expires_at;

        $this->loadChart();

        $this->profit_balance = BotProfitCycle::where('user_id', auth()->id())
            ->where('status', 'credited')
            ->sum('profit_amount');

        $this->deposit_bonus = auth()->user()->deposit_bonus_balance;
    }

    public function loadChart() {
        $days = collect(range(6, 0))->map(function ($i) {
            return Carbon::now()->subDays($i)->format('Y-m-d');
        });

        $data = WalletLedger::where('user_id', auth()->id())
            ->where('created_at', '>=', now()->subDays(7))
            ->select(
                DB::raw('DATE(created_at) as date'),
                DB::raw('SUM(credit) as total_credit'),
                DB::raw('SUM(debit) as total_debit')
            )
            ->groupBy('date')
            ->get()
            ->keyBy('date');

        $credits = [];
        $debits = [];

        foreach ($days as $day) {
            $credits[] = $data[$day]->total_credit ?? 0;
            $debits[] = $data[$day]->total_debit ?? 0;
        }

        $this->chartData = [
            'labels' => $days,
            'credits' => $credits,
            'debits' => $debits,
        ];


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