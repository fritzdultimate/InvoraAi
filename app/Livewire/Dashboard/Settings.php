<?php

namespace App\Livewire\Dashboard;

use Livewire\Component;

class Settings extends Component {

    public $id_front;
    public $id_back;
    public $selfie;
    public $address_proof;
    public $tab = 'profile';
    public $sessions = [];
    public $email_notifications = true;
    public $deposit_alerts = true;
    public $withdrawal_alerts = true;
    public $security_alerts = true;

    public function mount() {
        $this->sessions = collect(session()->all())->map(function ($session, $key) {
            return [
                'id' => $key,
                'device' => request()->userAgent(),
                'ip' => request()->ip(),
                'last_active' => now()->diffForHumans(),
                'current' => true
            ];
        })->toArray();

        $user = auth()->user();

        $this->email_notifications = $user->email_notifications;
        $this->deposit_alerts = $user->deposit_alerts;
        $this->withdrawal_alerts = $user->withdrawal_alerts;
        $this->security_alerts = $user->security_alerts;
    }

    public function saveNotifications() {
        auth()->user()->update([
            'email_notifications' => $this->email_notifications,
            'deposit_alerts' => $this->deposit_alerts,
            'withdrawal_alerts' => $this->withdrawal_alerts,
            'security_alerts' => $this->security_alerts,
        ]);

        $this->dispatch('success', message: 'Preferences updated');
    }

    public function logoutOthers() {
        auth()->logoutOtherDevices($this->password);

        $this->dispatch('success', message: 'Logged out other devices');
    }
    public function render()
    {
        return view('livewire.dashboard.settings');
    }
}
