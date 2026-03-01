<?php

namespace App\Livewire\Dashboard;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;

class Settings extends Component {

    public $id_front;
    public $id_back;
    public $selfie;
    public $address_proof;
    public $tab = 'security';
    public $sessions = [];
    public $email_notifications = true;
    public $deposit_alerts = true;
    public $withdrawal_alerts = true;
    public $security_alerts = true;
    public $confirm_password;

    public function mount() {
        $this->loadSessions();


        $user = auth()->user();

        $this->email_notifications = $user->email_notifications;
        $this->deposit_alerts = $user->deposit_alerts;
        $this->withdrawal_alerts = $user->withdrawal_alerts;
        $this->security_alerts = $user->security_alerts;
    }

    public function loadSessions() {
        $this->sessions = DB::table('sessions')
            ->where('user_id', auth()->id())
            ->orderBy('last_activity', 'desc')
            ->get()
            ->map(function ($session) {

                $agent = $session->user_agent;

                return [
                    'id' => $session->id,
                    'ip' => $session->ip_address,
                    'device' => $this->parseDevice($agent),
                    'is_mobile' => $this->isMobile($agent),
                    'last_active' => \Carbon\Carbon::createFromTimestamp($session->last_activity)->diffForHumans(),
                    'current' => $session->id === session()->getId(),
                ];
            });
    }

    private function parseDevice($agent) {
        if (str_contains($agent, 'Android')) return 'Android';
        if (str_contains($agent, 'iPhone')) return 'iPhone';
        if (str_contains($agent, 'iPad')) return 'iPad';

        if (str_contains($agent, 'Windows')) return 'Windows PC';
        if (str_contains($agent, 'Mac')) return 'Mac';
        if (str_contains($agent, 'Linux')) return 'Linux';


        return 'Unknown Device';
    }

    private function isMobile($agent) {
        return str_contains($agent, 'Android') ||
            str_contains($agent, 'iPhone') ||
            str_contains($agent, 'iPad');
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

        // if (!Hash::check($this->confirm_password, auth()->user()->password)) {
        //     $this->addError('confirm_password', 'Password incorrect');
        //     return;
        // }


        DB::table('sessions')
            ->where('user_id', auth()->id())
            ->where('id', '!=', session()->getId())
            ->delete();

        $this->loadSessions();

        $this->dispatch('success', message: 'Logged out other devices');
    }

    public function logoutSession($id) {
        DB::table('sessions')->where('id', $id)->delete();

        $this->loadSessions();

        $this->dispatch('success', message: 'Device logged out');
    }
    public function render()
    {
        return view('livewire.dashboard.settings');
    }
}
