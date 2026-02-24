<?php

namespace App\Enums;

enum LedgerAsset: string
{
    case MAIN = 'main';
    case DEPOSIT = 'deposit';
    case PROFIT = 'profit';
    case REFERRALBONUS = 'referral_bonus';
    case LOCKEDBALANCE = 'locked_balance';
}
