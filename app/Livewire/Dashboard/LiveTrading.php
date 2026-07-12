<?php

namespace App\Livewire\Dashboard;
use App\Models\LiveTrade;
use App\Models\Waitlist;
use App\Services\HyperliquidService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class LiveTrading extends Component {

    public $data = [];
    use WithPagination;

    public string $coin = 'all';
    public string $search = '';
    public int $perPage = 15;
    public ?string $visitedAt = null;

    protected $queryString = ['coin', 'search'];

    public function poll() {
        $this->syncIfDue();
    }

    protected function syncIfDue() {
        Cache::lock('hyperliquid-sync-lock', 65)->get(function () {
            if (Cache::has('hyperliquid-last-synced')) {
                return;
            }

            HyperliquidService::syncAll();

            Cache::put('hyperliquid-last-synced', now(), now()->addSeconds(60));
        });
    }

    public function updatingCoin() {
        $this->resetPage();
    }
    public function updatingSearch() {
        $this->resetPage();
    }

    public function resetFilters() {
        $this->reset('coin', 'search');
    }

    public function mount() {
        $this->visitedAt = now()->toDateTimeString();
    }

    public function render() {
        $trades = LiveTrade::query()
            ->whereNotNull('pair')
            ->where('pair', '!=', '')
            ->whereNotNull('side')
            ->where('side', '!=', '')
            ->where('price', '>', 0)
            ->where('amount', '>', 0)
            ->where('amount_usd', '>', 0)
            ->when($this->coin !== 'all', fn ($q) => $q->where('coin', $this->coin))
            ->when($this->search, fn ($q) => $q->where(fn ($q2) =>
                $q2->where('pair', 'like', "%{$this->search}%")
                ->orWhere('tx_hash', 'like', "%{$this->search}%")
            ))
            ->latest('block_time')
            ->paginate($this->perPage);

        return view('livewire.dashboard.live-trading', [
            'trades' => $trades,
            'coinOptions' => LiveTrade::query()
                ->whereNotNull('coin')
                ->where('coin', '!=', '')
                ->distinct()
                ->pluck('coin')
                ->filter(),
        ]);
    }

    public function wait() {
        if (!auth()->user()->waitlist()->exists()) {
            Waitlist::create([
                'user_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            $this->dispatch('success', message: "You’ll be notified when live trading launches.");
        }
    }

}