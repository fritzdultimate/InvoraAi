<?php

namespace App\Services\Wallet;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\CustomSetting;
use App\Models\User;
use Illuminate\Support\Facades\DB;

/**
 * Moves money from the profit or referral balance into the main balance.
 * Used by the Withdrawal page and the Wallet page, so the rules are the same everywhere.
 */
class BalanceConversionService
{
    public const SOURCES = [
        'profit' => [
            'asset' => LedgerAsset::PROFIT,
            'reference' => LedgerReference::PROFITTRANSFER,
            'column' => 'profit_balance',
            'label' => 'profit',
        ],
        'referral' => [
            'asset' => LedgerAsset::REFERRALBONUS,
            'reference' => LedgerReference::REFERRALBONUSTRANSFER,
            'column' => 'referral_balance',
            'label' => 'referral bonus',
        ],
    ];

    public static function minimum(): float
    {
        return (float) (CustomSetting::get('minimum_conversion') ?? 10);
    }

    /**
     * @throws \DomainException with a message that can be shown to the user
     */
    public static function convert(User $user, string $from, float $amount): float
    {
        $source = self::SOURCES[$from] ?? throw new \DomainException('Choose a balance to convert from.');
        $amount = round($amount, 2);

        if ($user->suspended_at) {
            throw new \DomainException('Your account has been suspended.');
        }

        if ($amount <= 0) {
            throw new \DomainException('Invalid amount');
        }

        if ($amount < self::minimum()) {
            throw new \DomainException('Minimum conversion amount is $' . number_format(self::minimum(), 2));
        }

        DB::transaction(function () use ($user, $amount, $source) {
            // lock the row so two quick conversions can't both spend the same money
            $user = User::lockForUpdate()->find($user->id);

            if ((float) $user->{$source['column']} < $amount) {
                throw new \DomainException('Insufficient ' . $source['label'] . ' balance');
            }

            WalletService::debit($user, $amount, $source['reference'], null, $source['label'] . ' transfer to main balance', $source['asset']);
            WalletService::credit($user, $amount, $source['reference'], null, $source['label'] . ' transfer from ' . $source['label'] . ' balance', LedgerAsset::MAIN);
        });

        return $amount;
    }
}
