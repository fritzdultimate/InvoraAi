<?php 

namespace App\Livewire\Auth;

use App\Mail\VerifyEmailMail;
use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Mail;

#[Layout('components.layouts.auth')]
class VerifyNotice extends Component
{
    public $email;
    public $sent = false;

    public function mount() {
        $this->email = session('verify_email');
    }

    public function send() {
        $this->validate([
            'email' => 'required|email|exists:users,email'
        ]);

        $user = User::where('email', $this->email)->first();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login');
        }

        
        if ($user->last_verification_sent_at &&
            $user->last_verification_sent_at->diffInSeconds(now()) < 60) {
            $secondsLeft = 60 - $user->last_verification_sent_at->diffInSeconds(now());

            $this->addError('email', "Please wait {$secondsLeft}s before requesting another email.");
            return;
        }

        
        $url = URL::temporarySignedRoute(
            'verification.link',
            now()->addMinutes(10),
            [
                'user' => $user->id,
                'hash' => sha1($user->email),
            ]
        );

        
        Mail::to($user->email)->send(
            new VerifyEmailMail($url)
        );

        
        $user->update([
            'last_verification_sent_at' => now()
        ]);

        $this->sent = true;
        session()->flash('status', 'Verification link has been sent to your email.');
    }

    public function render() {
        return view('livewire.auth.verify-notice');
    }
}