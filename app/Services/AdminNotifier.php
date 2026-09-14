<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Mail\Mailable;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

/**
 * Central place for "tell the admins" alerts (deposit approved, withdrawal
 * requested/approved/rejected, etc). Every money-moving event should notify
 * admins through here instead of hand-rolling `Mail::to(...)` with a
 * hardcoded address, so the recipient list only has to be right in one
 * place.
 */
class AdminNotifier
{
    /**
     * Everyone who should receive admin alerts: primarily users with the
     * `admin` Spatie role. Falls back to a single configured address
     * (ADMIN_ALERT_EMAIL in .env) so alerts never silently go nowhere if
     * no admin role has been assigned yet.
     */
    public static function recipients(): array
    {
        $emails = User::role('admin')
            ->whereNotNull('email')
            ->pluck('email')
            ->filter()
            ->unique()
            ->values()
            ->all();

        if (empty($emails) && $fallback = config('mail.admin_alert_email')) {
            $emails = [$fallback];
        }

        return $emails;
    }

    /**
     * Send an admin alert mailable to every admin recipient. Never throws —
     * a misconfigured admin list should not take down a deposit/withdrawal
     * flow, it should just be logged so it gets noticed and fixed.
     */
    public static function notify(Mailable $mailable): void
    {
        $recipients = self::recipients();

        if (empty($recipients)) {
            Log::warning('AdminNotifier: no admin recipients configured, alert not sent.', [
                'mailable' => get_class($mailable),
            ]);

            return;
        }

        try {
            Mail::to($recipients)->send($mailable);
        } catch (\Throwable $e) {
            Log::error('AdminNotifier: failed to send admin alert.', [
                'mailable' => get_class($mailable),
                'error' => $e->getMessage(),
            ]);
        }
    }
}
