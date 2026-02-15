<?php

namespace App\Enums;

enum WithdrawalStatus: string
{
    case PENDING   = 'pending';
    case REVIEW    = 'review';
    case PROCESSING = 'processing';
    case COMPLETED = 'completed';
    case FAILED    = 'failed';
    case CANCELLED = 'cancelled';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Pending',
            self::REVIEW => 'Under Review',
            self::PROCESSING => 'Processing',
            self::COMPLETED => 'Completed',
            self::FAILED => 'Failed',
            self::CANCELLED => 'Cancelled',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::COMPLETED => 'success',
            self::REVIEW => 'warning',
            self::CANCELLED => 'danger',
            self::PROCESSING => 'info',
            self::FAILED => 'danger',
        };
    }

    public function isFinal(): bool
    {
        return in_array($this, [
            self::COMPLETED,
            self::FAILED,
            self::CANCELLED,
        ], true);
    }
}
