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
    public $country;

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

    public function updateChanges() {
        $this->validate([
            'phone' => ['required', 'string', 'min:7', 'max:20'],
            'country' => ['required', 'string'],
        ], [
            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Phone number looks too short.',
            'country.required' => 'Please select your country.',
        ]);

        auth()->user()->update([
            'country' => $this->country,
            'phone_number' => $this->phone
        ]);

        $this->dispatch('success', message: 'Details updated successfully.');
    }

    public function mount() {
        $user = auth()->user();
        $this->fullname = $user->firstname . ' ' . $user->lastname;
        $this->phone = $user->phone_number;
        $this->country = $user->country;
    }
    public function render() {
        return view('livewire.dashboard.profile');
    }
}
