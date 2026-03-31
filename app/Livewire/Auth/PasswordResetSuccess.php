<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class PasswordResetSuccess extends Component {
    public function render(): \Illuminate\View\View {
        return view('livewire.auth.password-reset-success');
    }
}
