<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth', [
    'title' => 'Password Updated',
    'subtitle' => 'You can now login'
])]
class PasswordResetSuccess extends Component {
    public function render(): \Illuminate\View\View {
        return view('livewire.auth.password-reset-success');
    }
}
