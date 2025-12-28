<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth', [
    'title' => 'Account Verified',
    'subtitle' => "Your account is now active. You can login."
])]
class VerificationSuccess extends Component
{
    public function render(): \Illuminate\View\View
    {
        return view('livewire.auth.verification-success');
    }
}
