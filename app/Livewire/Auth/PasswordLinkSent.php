<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth', [
    'title' => 'Reset Link Sent',
    'subtitle' => 'Check your email'
])]
class PasswordLinkSent extends Component {
    public function render(): \Illuminate\View\View
    {
        return view('livewire.auth.password-link-sent');
    }
}
