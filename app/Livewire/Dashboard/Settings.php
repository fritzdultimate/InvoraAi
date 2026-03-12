<?php

namespace App\Livewire\Dashboard;

use App\Models\KycVerification;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Livewire\Component;
use Livewire\WithFileUploads;

class Settings extends Component {
    use WithFileUploads;

    public $id_front;
    public $id_back;
    public $selfie;
    public $address;
    public $tab = 'security';
    public $sessions = [];
    public $email_notifications = true;
    public $deposit_alerts = true;
    public $withdrawal_alerts = true;
    public $security_alerts = true;
    public $confirm_password;
    public $country;
    public $document_type;
    public $dob;

    public $twofactor;
    public $notifyLoginAttempts;

    public $notifyDepositAlerts;
    public $notifyWithdrawalAlerts;
    public $notifySecurityAlerts;
    public $notifyEmailNotification;


    public function updatedTwoFactor($value) {
        auth()->user()->update([
            'two_factor_enable' => $value
        ]);
    }

    public function updatedNotifyLoginAttempts($value) {
        auth()->user()->update([
            'notify_login_attempts' => $value
        ]);
    }

    public function updatedNotifyDepositAlerts($value) {
        auth()->user()->update([
            'notify_depsoit_alerts' => $value
        ]);
    }

    public function updatedNotifyWithdrawalAlerts($value) {
        auth()->user()->update([
            'notify_withdrawal_alerts' => $value
        ]);
    }

    public function updatedNotifySecurityAlerts($value) {
        auth()->user()->update([
            'notify_security_alerts' => $value
        ]);
    }

    public function updatedNotifyEmailNotification($value) {
        auth()->user()->update([
            'notify_email_notifications' => $value
        ]);
    }


    public function mount() {
        $this->loadSessions();


        $user = auth()->user();

        $this->email_notifications = $user->email_notifications;
        $this->deposit_alerts = $user->deposit_alerts;
        $this->withdrawal_alerts = $user->withdrawal_alerts;
        $this->security_alerts = $user->security_alerts;

        $this->twofactor = $user->two_factor_enable;
        $this->notifyLoginAttempts = $user->notify_login_attempts;

        $this->notifyDepositAlerts = $user->notify_depsoit_alerts;
        $this->notifyWithdrawalAlerts = $user->notify_withdrawal_alerts;
        $this->notifySecurityAlerts = $user->notify_security_alerts;
        $this->notifyEmailNotification = $user->notify_email_notifications;


        $this->country = $user->country;
        $this->dob = $user->dob?->format('Y-m-d');
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

    public function submit() {
        sleep(5);
        $this->validate([
            'address' => 'required|string|max:255|min:6',
            'country' => 'required|string',
            // 'date_of_birth' => 'required|date',
            'document_type' => 'required',
            'id_front' => 'required|image|max:4096',
            'id_back' => 'required|image|max:4096',
        ]);
        $this->resetErrorBag();

        abort_if(auth()->user()->kyc && auth()->user()->kyc->status != 'rejected', 403);

        $kyc = KycVerification::create([
            'user_id' => auth()->id(),
            'address' => $this->address,
            'country' => $this->country,
            'date_of_birth' => $this->dob,
            'document_type' => $this->document_type,
            'document_front' => $this->id_front->store('kyc', 'local'),
            'document_back' => $this->id_back?->store('kyc', 'local'),
            'status' => 'pending',
        ]);

        if($kyc) {
            auth()->user()->update([
                'kyc_status' => 'pending',
                'kyc_submitted_at' => now()
            ]);
            auth()->user()->refresh();

            $this->dispatch('success', message: 'Your details are under review. We\'ll notify you as soon as verification is complete.');

            $this->reset([
                'address',
                'country',
                'id_front',
                'document_type',
                'id_back'
            ]);

            // Mail::to(auth()->user()->email)->send(new KycSubmittedMail(auth()->user()));
        }
    }

    public function render() {
        return view('livewire.dashboard.settings');
    }
}
