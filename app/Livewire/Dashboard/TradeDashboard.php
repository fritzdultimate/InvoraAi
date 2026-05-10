<?php

namespace App\Livewire\Dashboard;
use App\Models\Trade;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.app')]
class TradeDashboard extends Component
{

    public $trades;
    public $fundingRates = [];
    public $loading = false;
    public $error = null;

    protected $listeners = ['refreshTrades' => '$refresh'];

    public function loadTrades()
    {
        $this->trades = Cache::remember(
            'live-trades',
            2,
            fn () => Trade::with('asset')
                ->latest()
                ->take(20)
                ->get()
        );
    }

    public function mount() {
        $this->loadTrades();
    }

    public function refreshTrades() {
        $this->loadTrades();
    }

    public function render() {
        return view('livewire.dashboard.trade-dashboard');
    }

}
