<?php

namespace App\Livewire\Dashboard;
use App\Models\LiveTrade;
use App\Models\Waitlist;
use App\Services\BitqueryService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;
use Livewire\WithPagination;

class LiveTrading extends Component {

    public $data = [];
    use WithPagination;

    public string $network = 'all';
    public string $dex = 'all';
    public string $search = '';
    public int $perPage = 15;
    public ?string $visitedAt = null;

    protected $queryString = ['network', 'dex', 'search'];

    public function poll() {
        $this->syncIfDue();
    }

    protected function syncIfDue() {
        Cache::lock('bitquery-sync-lock', 65)->get(function () {
            if (Cache::has('bitquery-last-synced')) {
                return;
            }

            BitqueryService::syncAll();

            Cache::put('bitquery-last-synced', now(), now()->addSeconds(60));
        });
    }

    public function updatingNetwork() { 
        $this->resetPage(); 
    }
    public function updatingDex() { 
        $this->resetPage(); 
    }
    public function updatingSearch() { 
        $this->resetPage(); 
    }

    public function resetFilters() {
        $this->reset('network', 'dex', 'search');
    }

    public function mount() {
        $this->visitedAt = now()->toDateTimeString();
        $this->data = BitqueryService::getTrades();

        // cache()->remember('dex_trades', 10, fn () => $this->getTrades());
    }

    public function render() {
        $trades = LiveTrade::query()
            ->whereNotNull('pair')
            ->where('pair', '!=', '')
            ->whereNotNull('dex')
            ->where('dex', '!=', '')
            ->whereNotNull('side')
            ->where('side', '!=', '')
            ->where('price', '>', 0)
            ->where('amount', '>', 0)
            ->where('amount_usd', '>', 0)
            // ->where('created_at', '>=', $this->visitedAt)
            ->when($this->network !== 'all', fn ($q) => $q->where('network', $this->network))
            ->when($this->dex !== 'all', fn ($q) => $q->where('dex', $this->dex))
            ->when($this->search, fn ($q) => $q->where(fn ($q2) =>
                $q2->where('pair', 'like', "%{$this->search}%")
                ->orWhere('tx_hash', 'like', "%{$this->search}%")
            ))
            ->latest('block_time')
            ->paginate($this->perPage);

        return view('livewire.dashboard.live-trading', [
            'trades' => $trades,
            'dexOptions' => LiveTrade::query()
                ->whereNotNull('dex')
                ->where('dex', '!=', '')
                ->distinct()
                ->pluck('dex')
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
