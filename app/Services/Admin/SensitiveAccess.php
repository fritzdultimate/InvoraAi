<?php

namespace App\Services\Admin;

use App\Mail\AdminAlertMail;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

// extra check before an admin can open payment gateway / custom settings pages
class SensitiveAccess
{
    public const MINUTES = 15;

    public static function isVerified(): bool {
        $verifiedAt = session('sensitive_verified_at');
        $verifiedBy = session('sensitive_verified_by');

        return $verifiedAt
            && $verifiedBy === auth()->id()
            && now()->timestamp - $verifiedAt < self::MINUTES * 60;
    }

    public static function markVerified(): void {
        session([
            'sensitive_verified_at' => now()->timestamp,
            'sensitive_verified_by' => auth()->id(),
        ]);
    }

    // returns false when a code was sent less than a minute ago
    public static function sendEmailCode(User $user): bool {
        if (Cache::has("sensitive-code-sent:{$user->id}")) {
            return false;
        }

        $code = (string) random_int(100000, 999999);

        Cache::put("sensitive-code:{$user->id}", Hash::make($code), now()->addMinutes(10));
        Cache::put("sensitive-code-sent:{$user->id}", true, now()->addMinute());

        Mail::to($user->email)->send(new AdminAlertMail(
            subjectLine: 'Your admin security code',
            badge: 'SECURITY CODE',
            badgeColor: 'warning',
            heading: "Your code is {$code}",
            intro: 'Someone asked to open payment gateway or custom settings in the admin panel. The code expires in 10 minutes. If this was not you, change your password now.',
            rows: [
                'Admin' => $user->email,
                'IP address' => (string) request()->ip(),
                'Time' => now()->format('M j, Y g:i A'),
            ],
        ));

        return true;
    }

    public static function checkEmailCode(User $user, string $code): bool {
        $hash = Cache::get("sensitive-code:{$user->id}");

        if (! $hash || ! Hash::check($code, $hash)) {
            return false;
        }

        Cache::forget("sensitive-code:{$user->id}");

        return true;
    }
}
