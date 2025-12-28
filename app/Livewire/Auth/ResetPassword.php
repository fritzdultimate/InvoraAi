<?php

namespace App\Livewire\Auth;

use Illuminate\Validation\ValidationException;
use Livewire\Component;
use Livewire\Attributes\Layout;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Hash;

#[Layout('components.layouts.auth', [
    'title' => 'Reset Password',
    'subtitle' => 'Create a new password'
])]
class ResetPassword extends Component {
    public $email;
    public $token;
    public $password;
    public $password_confirmation;

    public function mount($token, $email) {
        $this->token = $token;
        $this->email = $email;
    }

    protected function rules() {
        return [
            'email' => ['required', 'email'],
            'password' => ['required', 'confirmed', 'min:8'],
        ];
    }

    public function resetPassword() {
        $this->validate();

        $status = Password::reset(
            [
                'email' => $this->email,
                'password' => $this->password,
                'password_confirmation' => $this->password_confirmation,
                'token' => $this->token,
            ],
            function ($user) {
                $user->password = Hash::make($this->password);
                $user->save();
            }
        );

        if ($status !== Password::PASSWORD_RESET) {
            throw ValidationException::withMessages([
                'password' => __($status),
            ]);
        }

    }
}
        // if ($status === Password::PASSWORD_RESET) {
        //     return redirect()->route('password.reset.success');
        // }

        // if($status === Password::InvalidUser) {6
