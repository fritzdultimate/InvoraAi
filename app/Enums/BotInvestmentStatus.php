<?php

namespace App\Enums;

enum BotInvestmentStatus: string {
    case ACTIVE = 'active';
    case COMPLETED = 'completed';
    case TERMINATED = 'terminated';
}
