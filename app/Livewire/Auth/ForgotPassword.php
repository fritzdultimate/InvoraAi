<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Password;

#[Layout('components.layouts.auth', [
    'title' => 'Forgot Password',
    'subtitle' => 'Reset access to your account'
])]
class ForgotPassword extends Component
{
    public $email = '';

    protected function rules()
    {
        return [
            'email' => ['required', 'email', 'exists:users,email'],
        ];
    }

    public function sendResetLink() {
        $this->validate();

        $status = Password::sendResetLink([
            'email' => $this->email,
        ]);

        if ($status === Password::RESET_LINK_SENT) {
            return redirect()->route('password.link.sent');
        }

        $this->addError('email', __($status));
    }

    public function render(): \Illuminate\View\View {
        return view('livewire.auth.forgot-password');
    }
}
