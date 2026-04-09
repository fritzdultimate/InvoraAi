<?php

namespace App\Livewire\Landing;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.landing')]
class TradingBots extends Component
{
    public function render(): \Illuminate\View\View
    {
        return view('livewire.landing.trading-bots');
    }
}
