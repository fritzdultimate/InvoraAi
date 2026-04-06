<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;

#[Layout('components.layouts.auth')]
class VerificationSuccess extends Component
{
    public $success = true;
    public $error = null;
    public $email;

    public function render(): \Illuminate\View\View {
        return view('livewire.auth.verification-success');
    }
}
