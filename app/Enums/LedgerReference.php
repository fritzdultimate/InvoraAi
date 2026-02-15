<?php

namespace App\Enums;

enum LedgerReference: string
{
    case DEPOSIT = 'deposit';
    case WITHDRAWAL = 'withdrawal';
    case BOT_PROFIT = 'bot_profit';
    case BOT_INVESTMENT = 'bot_investment';
    case BOT_TERMINATION = 'bot_termination';
    case LICENSE_PURCHASE = 'license_purchase';
}
