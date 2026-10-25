<?php

namespace App\Services\Admin;

use App\Models\AdminLog;
use Filament\Facades\Filament;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class AdminLogger
{
    // values never written to the log, only that they changed
    private const MASKED = [
        'password',
        'remember_token',
        'two_factor_secret',
        'two_factor_recovery_codes',
        'admin_2fa_secret',
        'admin_2fa_recovery_codes',
        'api_key',
        'ipn_secret',
    ];

    // models whose changes are not worth logging
    private const IGNORED = [
        AdminLog::class,
        \Illuminate\Notifications\DatabaseNotification::class,
    ];

    public static function record(
        string $event,
        ?Model $subject = null,
        ?string $description = null,
        array $old = [],
        array $new = [],
        ?int $userId = null,
    ): void {
        try {
            AdminLog::create([
                'user_id' => $userId ?? auth()->id(),
                'event' => $event,
                'subject_type' => $subject ? get_class($subject) : null,
                'subject_id' => $subject?->getKey(),
                'description' => $description ? Str::limit($description, 250) : null,
                'old_values' => $old ? self::mask($old) : null,
                'new_values' => $new ? self::mask($new) : null,
                'ip_address' => request()->ip(),
                'user_agent' => Str::limit((string) request()->userAgent(), 250),
                'url' => Str::limit(self::pageUrl(), 250),
            ]);
        } catch (\Throwable $e) {
            // a broken log must never stop the admin action itself
            Log::error('Admin log failed: ' . $e->getMessage());
        }
    }

    public static function modelEvent(string $event, Model $model): void {
        if (! self::isAdminRequest() || in_array(get_class($model), self::IGNORED)) {
            return;
        }

        $old = [];
        $new = [];

        if ($event === 'created') {
            $new = $model->getAttributes();
        } elseif ($event === 'updated') {
            $raw = $model->getRawOriginal();

            // skip "changes" like 1 -> true that are the same value
            $new = collect($model->getChanges())
                ->except(['updated_at'])
                ->reject(fn ($value, $key) => is_bool($value) && isset($raw[$key]) && (int) $raw[$key] === (int) $value)
                ->all();

            if (empty($new)) {
                return;
            }

            $old = array_intersect_key($raw, $new);
        } else {
            $old = $model->getRawOriginal();
        }

        self::record($event, $model, class_basename($model) . " #{$model->getKey()} {$event}", $old, $new);
    }

    public static function isAdminRequest(): bool {
        return Filament::isServing() && auth()->check();
    }

    // admin pages save through livewire, so the page the admin was on is the referer
    private static function pageUrl(): string {
        if (request()->is('livewire/*') && request()->headers->has('referer')) {
            return (string) request()->headers->get('referer');
        }

        return request()->fullUrl();
    }

    private static function mask(array $values): array {
        foreach ($values as $key => $value) {
            if (in_array($key, self::MASKED)) {
                $values[$key] = $value === null ? null : '******';
            }
        }

        return $values;
    }
}
