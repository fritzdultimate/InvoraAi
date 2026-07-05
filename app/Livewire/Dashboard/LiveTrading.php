<?php

namespace App\Livewire\Dashboard;
use App\Models\LiveTrade;
use App\Models\Waitlist;
use App\Services\BitqueryService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\WithPagination;

class LiveTrading extends Component {

    public $data = [];
    use WithPagination;

    public string $network = 'all';
    public string $dex = 'all';
    public string $search = '';
    public int $perPage = 15;

    protected $queryString = ['network', 'dex', 'search'];

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
        $this->data = BitqueryService::getTrades();

        // cache()->remember('dex_trades', 10, fn () => $this->getTrades());
    }

    public function render() {
        $trades = LiveTrade::query()
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
            'dexOptions' => LiveTrade::query()->distinct()->pluck('dex')->filter(),
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
