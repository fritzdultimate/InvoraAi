<?php

namespace App\Enums;

enum LedgerReference: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';
    // case LOCKEDBALANCE = 'locked_balance';
    case INVESTMENT_PROFIT = 'investment_profit';
    case BOT_INVESTMENT = 'bot_investment';
    case BOT_INVESTMENT_COMPLETED = 'bot_investment_completed';
    case BOT_TERMINATION = 'bot_termination';
    case BOT_TERMINATION_FEE = 'bot_termination_fee';
    case LICENSE_PURCHASE = 'license_purchase';
}
