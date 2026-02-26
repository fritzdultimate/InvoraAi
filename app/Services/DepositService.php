<?php

namespace App\Services;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\CustomSetting;
use App\Services\Wallet\WalletService;


class DepositService {
    public static function depositBonus($deposit) {
        $bonus = (float) CustomSetting::get('deposit_bonus', 0);
        $bonusDuration = (int) CustomSetting::get('deposit_bonus_duration_days', 0);

        if($bonus <= 0) return;

        $deposit->update([
            'bonus' => $bonus,
            // 'bonus_expires_at' => now()->addDays($bonusDuration),
        ]);

        WalletService::credit(
            $deposit->user,
            $bonus,
            LedgerReference::DEPOSITBONUS,
            $deposit->id,
            "deposit received",
            LedgerAsset::DEPOSITBONUSBALANCE
        );

        // email deposit received bonus
        // event(new DepositBonusReceived($deposit));
    }
}