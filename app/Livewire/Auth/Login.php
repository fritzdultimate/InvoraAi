<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class  Login extends Component {
    public $email = '';


    /**
     * Mount the component.
     */
    public function render(): \Illuminate\View\View {
        return view('livewire.auth.login');
    }
}