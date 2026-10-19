<?php

namespace App\Services\Card;

use App\Enums\LedgerAsset;
use App\Models\CustomSetting;

/**
 * Program-wide card settings, stored in custom_settings and edited from
 * the "Card program" page in the admin panel.
 */
class CardProgram
{
    public const DEFAULTS = [
        'cards.enabled' => '0',
        'cards.program_open' => '1',
        'cards.require_kyc' => '1',
        'cards.require_active_license' => '0',
        'cards.allowed_balances' => 'main,deposit',
        'cards.cancel_until' => 'pending',
        'cards.notice' => '',
    ];

    public static function get(string $key): string
    {
        return (string) CustomSetting::get($key, self::DEFAULTS[$key] ?? '');
    }

    // master switch: when off, the Cards section is hidden from users entirely
    public static function isEnabled(): bool
    {
        return self::get('cards.enabled') === '1';
    }

    public static function isOpen(): bool
    {
        return self::get('cards.program_open') === '1';
    }

    public static function requiresKyc(): bool
    {
        return self::get('cards.require_kyc') === '1';
    }

    public static function requiresActiveLicense(): bool
    {
        return self::get('cards.require_active_license') === '1';
    }

    // balances a user may pay the pre-order fee from
    public static function allowedBalances(): array
    {
        $allowed = array_filter(explode(',', self::get('cards.allowed_balances')));

        return collect([LedgerAsset::MAIN, LedgerAsset::DEPOSIT])
            ->filter(fn ($asset) => in_array($asset->value, $allowed))
            ->values()
            ->all();
    }

    // last status at which the user can still cancel: pending, approved or never
    public static function cancelUntil(): string
    {
        return self::get('cards.cancel_until');
    }

    public static function notice(): string
    {
        return self::get('cards.notice');
    }
}
