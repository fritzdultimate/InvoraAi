<?php

namespace App\Enums;

enum LedgerAsset: string
{
    case MAIN = 'main';
    case DEPOSIT = 'deposit';
    case PROFIT = 'profit';
    case REFERRALBONUS = 'referral_bonus';
    case LOCKEDBALANCE = 'locked_balance';
    case DEPOSITBONUSBALANCE = 'deposit_bonus_balance';

    public function label(): string {
        $value = str_ends_with($this->value, '_balance')
            ? $this->value
            : $this->value . '_balance';

        return ucfirst(str_replace('_', ' ', $value));
    }
}
