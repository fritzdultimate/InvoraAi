<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;

#[Layout('components.layouts.auth')]
class  Login extends Component {
    public $email = '';
    public $password = '';
    public $remember = false;

    protected function rules() {
        return [
            'email' => ['required', 'email'],
            'password' => ['required'],
        ];
    }

    public function login() {
        // Validate input
        $credentials = $this->validate();

        // Attempt authentication
        if (! Auth::attempt($credentials, $this->remember)) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        session()->regenerate();

        $this->reset('password');

        return redirect()->intended(route('dashboard'));
    }


    /**
     * Mount the component.
     */
    public function render(): \Illuminate\View\View {
        return view('livewire.auth.login');
    }
}