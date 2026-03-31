<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class VerificationFailure extends Component
{
    public function render(): \Illuminate\View\View
    {
        return view('livewire.auth.verification-failure');
    }
}
