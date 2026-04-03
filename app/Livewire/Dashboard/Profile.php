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
    public $dob;

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

    try {
            $this->validate([
                'phone' => ['required', 'string', 'min:7', 'max:20'],
                'country' => ['required', 'string'],
                'dob' => ['required', 'date', 'before:today'],
                'fullname' => ['required', 'string', 'max:255'],
            ], [
                'phone.required' => 'Phone number is required.',
                'phone.min' => 'Phone number looks too short.',
                'country.required' => 'Please select your country.',
            ]);

            $nameParts = explode(' ', $this->fullname, 2);
            $firstName = $nameParts[0] ?? '';
            $lastName = $nameParts[1] ?? '';

            auth()->user()->update([
                'country' => $this->country,
                'phone_number' => $this->phone,
                'dob' => $this->dob,
                'firstname' => $firstName,
                'lastname' => $lastName
            ]);

            $this->dispatch('success', message: 'Profile updated successfully.');
        } catch(\Illuminate\Validation\ValidationException $e) {
            $this->dispatch('error', message: $e->getMessage());
        }
    }

    public function mount() {
        $user = auth()->user();
        $this->fullname = $user->firstname . ' ' . $user->lastname;
        $this->phone = $user->phone_number;
        $this->country = $user->country;
        
        $this->dob = $user->dob?->format('Y-m-d');
    }
    public function render() {
        return view('livewire.dashboard.profile');
    }
}
