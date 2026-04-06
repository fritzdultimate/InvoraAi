<?php

namespace App\Livewire\Auth;

use Livewire\Attributes\Layout;
use Livewire\Component;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

#[Layout('components.layouts.auth')]
class VerifyAction extends Component
{
    public $success = false;
    public $error = null;
    public $email = true;

    public function mount($user, $hash) {
        $user = User::findOrFail($user);

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('login');
        }

        // 🔐 Validate hash
        if (!hash_equals(sha1($user->email), $hash)) {
            $this->error = 'Invalid verification link.';
            return;
        }

        // ✅ Perform action (example: mark verified)
        $user->update([
            'email_verified_at' => now(),
        ]);

        // optional login
        // Auth::login($user);

        $this->success = true;
    }

    public function render() {
        return view('livewire.auth.verify-action');
    }
}