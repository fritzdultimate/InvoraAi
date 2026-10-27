<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => trim($this->firstname . ' ' . $this->lastname),
            'username' => $this->name,
            'email' => $this->email,
            'referral_code' => $this->affiliate_code,
            'country' => $this->country,
            'phone_number' => $this->phone_number,
            'dob' => $this->dob?->format('Y-m-d'),
            'kyc_status' => $this->kyc_status,
            'two_factor_enabled' => ! is_null($this->two_factor_confirmed_at),
            'preferences' => [
                'notify_login_attempts' => (bool) $this->notify_login_attempts,
                'notify_email_notifications' => (bool) $this->notify_email_notifications,
                'notify_deposit_alerts' => (bool) $this->notify_deposit_alerts,
                'notify_withdrawal_alerts' => (bool) $this->notify_withdrawal_alerts,
                'notify_security_alerts' => (bool) $this->notify_security_alerts,
            ],
            'created_at' => $this->created_at?->toIso8601String(),
        ];
    }
}
