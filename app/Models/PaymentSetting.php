<?php

namespace App\Models;

use App\Mail\AdminAlertMail;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class PaymentSetting extends Model {
    protected $guarded = [];

    protected static function booted(): void {
        static::saved(function (PaymentSetting $setting) {
            $changed = collect(['api_key' => 'API key', 'ipn_secret' => 'IPN secret'])
                ->filter(fn ($label, $field) => $setting->wasRecentlyCreated || $setting->wasChanged($field))
                ->values();

            if ($changed->isNotEmpty()) {
                $setting->sendChangeNotice($changed->all());
            }
        });
    }

    // only says what changed and who changed it, never the values themselves
    public function sendChangeNotice(array $fields): void {
        $to = config('services.gateway_alert_email');

        if (! $to) {
            return;
        }

        try {
            Mail::to($to)->send(new AdminAlertMail(
                subjectLine: 'Payment gateway keys changed',
                badge: 'SECURITY NOTICE',
                badgeColor: 'danger',
                heading: implode(' and ', $fields) . ' changed for ' . ucfirst($this->provider),
                intro: 'The payment gateway settings were just changed. If you did not expect this, check the admin panel right away.',
                rows: [
                    'Changed' => implode(', ', $fields),
                    'Changed by' => auth()->user()?->email ?? 'System',
                    'IP address' => (string) request()->ip(),
                    'Time' => now()->format('M j, Y g:i A T'),
                ],
                url: url('/admin/admin-logs'),
                ctaLabel: 'View Admin Logs',
            ));
        } catch (\Throwable $e) {
            Log::error('Gateway change notice failed: ' . $e->getMessage());
        }
    }
}
