<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class MarketHub extends Component {
    public function goToCex() {
        return redirect()->route('cex.live-trading');
    }

    public function goToDex() {
        return redirect()->route('dex.live-trading');
    }

    public function render()
    {
        return view('livewire.dashboard.market-hub');
    }
}