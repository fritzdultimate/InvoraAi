<?php

namespace App\Livewire\Dashboard;
use App\Models\Waitlist;
use App\Services\BitqueryService;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;

class LiveTrading extends Component
{

    public $data = [];

    public function mount()
    {
        $this->data = BitqueryService::getTrades();

        // cache()->remember('dex_trades', 10, fn () => $this->getTrades());
    }

    public function render()
    {
        return view('livewire.dashboard.live-trading');
    }

    public function wait()
    {
        if (!auth()->user()->waitlist()->exists()) {
            Waitlist::create([
                'user_id' => Auth::id(),
                'ip' => request()->ip()
            ]);

            $this->dispatch('success', message: "You’ll be notified when live trading launches.");
        }
    }

}
