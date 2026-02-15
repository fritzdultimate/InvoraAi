<?php

namespace App\Enums;

enum BotLicenseStatus: string {
    case ACTIVE = 'active';
    case EXPIRED = 'expired';
    case REVOKED = 'revoked';
    case PENDING = 'pending';
}
