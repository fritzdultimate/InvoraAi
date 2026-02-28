<?php

namespace App\Livewire\Dashboard;

use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Profile extends Component
{
    public $fullname;
    public $phone;
    public $current_password;
    public $password;
    public $password_confirmation;

    public function updatePassword() {
        $user = auth()->user();

        $this->validate([
            'current_password' => ['required'],
            'password' => ['required', 'min:6', 'confirmed'],
        ]);

        if (!Hash::check($this->current_password, $user->password)) {
            $this->addError('current_password', 'Current password is incorrect.');
            return;
        }

        $user->update([
            'password' => Hash::make($this->password)
        ]);

        $this->reset([
            'current_password',
            'password',
            'password_confirmation'
        ]);

        $this->dispatch('success', message: 'Password updated successfully.');
    }

    public function mount() {
        $user = auth()->user();
        $this->fullname = $user->firstname . ' ' . $user->lastname;
        $this->phone = $user->phone_number;
    }
    public function render() {
        return view('livewire.dashboard.profile');
    }
}
