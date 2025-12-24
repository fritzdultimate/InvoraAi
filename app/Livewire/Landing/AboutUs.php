<?php

namespace App\Livewire\Landing;

use Livewire\Attributes\Layout;
use Livewire\Attributes\Locked;
use Livewire\Component;

#[Layout('components.layouts.guest')]
class  AboutUs extends Component {
    #[Locked]
    public bool $twoFactorEnabled;


    /**
     * Mount the component.
     */
    public function render(): \Illuminate\View\View {
        return view('livewire.landing.about-us');
    }
}