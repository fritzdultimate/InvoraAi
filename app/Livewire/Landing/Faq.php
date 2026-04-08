<?php

namespace App\Livewire\Landing;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.landing')]
class Faq extends Component
{


    /**
     * Mount the component.
     */
    public function render(): \Illuminate\View\View {
        return view('livewire.landing.faq');
    }
}