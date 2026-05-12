<?php

namespace App\Livewire\Dashboard;
use App\Models\Trade;
use Illuminate\Support\Facades\Cache;
use Livewire\Attributes\Layout;
use Livewire\Component;
use Livewire\WithPagination;

#[Layout('components.layouts.app')]
class TradeDashboard extends Component {
    use WithPagination;

    public $fundingRates = [];
    public $loading = false;
    public $error = null;
    public $statusFilter = 'open';
    public $assetFilter = 'all';
    public $sortBy = 'latest';
    public $perPage = 20;

    protected $queryString = [
        'statusFilter' => ['except' => 'all'],
        'assetFilter' => ['except' => 'all'],
        'sortBy' => ['except' => 'latest'],
        'page' => ['except' => 1],
    ];

    protected $listeners = ['refreshTrades' => '$refresh'];

    public function getTrades() {
        $query = Trade::with('asset');

        if ($this->statusFilter === 'profit') {
            $query->where('total_net', '>', 0);
        } elseif ($this->statusFilter === 'loss') {
            $query->where('total_net', '<', 0);
        } elseif ($this->statusFilter !== 'all') {
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

        return $query->paginate($this->perPage);
    }


    public function refreshTrades() {
        $this->dispatch('$refresh');
    }

    public function updatedStatusFilter() {
        $this->resetPage();
    }
 
    public function updatedAssetFilter() {
        $this->resetPage();
    }
 
    public function updatedSortBy() {
        $this->resetPage();
    }

    public function updatedPerPage() {
        $this->resetPage();
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
            'trades' => $this->getTrades(),
            'availableAssets' => $this->getAvailableAssets()
        ]);
    }

}
