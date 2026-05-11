<?php

namespace App\Livewire\Dashboard;
use App\Models\Trade;
use App\Services\TradeSimulatorService;
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
    public $statusFilter = 'all';
    public $assetFilter = 'all';
    public $sortBy = 'latest';

    protected $listeners = ['refreshTrades' => '$refresh'];

    public function loadTrades()
    {
        // $this->trades = Cache::remember(
        //     'live-trades',
        //     2,
        //     fn () => Trade::with('asset')
        //         ->latest()
        //         ->take(20)
        //         ->get()
        // );

        $query = Trade::with('asset');
 
        if ($this->statusFilter !== 'all') {
            $query->where('status', $this->statusFilter);
        }
 
        if ($this->assetFilter !== 'all') {
            $query->whereHas('asset', function ($q) {
                $q->where('symbol', $this->assetFilter);
            });
        }

        switch ($this->sortBy) {
            case 'latest':
                $query->latest();
                break;
            case 'oldest':
                $query->oldest();
                break;
            case 'highest_profit':
                $query->orderBy('total_net', 'desc');
                break;
            case 'highest_loss':
                $query->orderBy('total_net', 'asc');
                break;
        }

        $this->trades = Cache::remember(
            'live-trades-' . $this->statusFilter . '-' . $this->assetFilter . '-' . $this->sortBy,
            2,
            fn() => $query->take(20)->get()
        );
    }

    public function mount() {
        $this->loadTrades();
    }

    public function refreshTrades() {
        $simulator = new TradeSimulatorService();
        Trade::where('status', 'open')
            ->inRandomOrder()
            ->take(rand(1, 4))
            ->get()
            ->each(function ($trade) use ($simulator) {

                $simulator->updateTrade($trade);
            });
        $this->loadTrades();
    }

    public function updatedStatusFilter() {
        $this->loadTrades();
    }
 
    public function updatedAssetFilter() {
        $this->loadTrades();
    }
 
    public function updatedSortBy() {
        $this->loadTrades();
    }

    public function getAvailableAssets() {
        return Cache::remember('available-assets', 60, function () {
            return Trade::with('asset')
                ->get()
                ->pluck('asset.symbol')
                ->unique()
                ->sort()
                ->values();
        });
    }

    public function render() {
        return view('livewire.dashboard.trade-dashboard', [
            'availableAssets' => $this->getAvailableAssets()
        ]);
    }

}
