<?php

namespace App\Livewire\Landing;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class  TradingExecution extends Component {


    /**
     * Mount the component.
     */
    public function render(): \Illuminate\View\View {
        return view('livewire.landing.trading-execution');
    }
}