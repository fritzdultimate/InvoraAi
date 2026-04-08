<?php

namespace App\Livewire\Landing;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.landing')]
class TradingExecution extends Component
{
    public function render(): \Illuminate\View\View
    {
        return view('livewire.landing.trading-execution');
    }
}
