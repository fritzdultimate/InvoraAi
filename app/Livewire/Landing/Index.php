<?php

namespace App\Livewire\Landing;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

// #[Layout('components.layouts.guest')]
#[Layout('components.layouts.landing', params: ['showPreloader' => true])]
class  Index extends Component {
    #[Locked]
    public bool $twoFactorEnabled;


    /**
     * Mount the component.
     */
    public function render(): \Illuminate\View\View {
        return view('livewire.landing.landing');
    }
}