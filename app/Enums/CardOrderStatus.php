<?php

namespace App\Enums;

use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum CardOrderStatus: string implements HasLabel, HasColor
{
    case PENDING = 'pending';
    case APPROVED = 'approved';
    case PROCESSING = 'processing';
    case SHIPPED = 'shipped';
    case ACTIVE = 'active';
    case CANCELLED = 'cancelled';
    case REJECTED = 'rejected';

    public function label(): string
    {
        return match ($this) {
            self::PENDING => 'Under review',
            self::APPROVED => 'Approved',
            self::PROCESSING => 'In production',
            self::SHIPPED => 'Shipped',
            self::ACTIVE => 'Active',
            self::CANCELLED => 'Cancelled',
            self::REJECTED => 'Rejected',
        };
    }

    // Filament colour names, also used as CSS modifiers on the dashboard
    public function color(): string
    {
        return match ($this) {
            self::PENDING => 'warning',
            self::APPROVED, self::PROCESSING => 'info',
            self::SHIPPED => 'primary',
            self::ACTIVE => 'success',
            self::CANCELLED => 'gray',
            self::REJECTED => 'danger',
        };
    }

    public function getLabel(): string
    {
        return $this->label();
    }

    public function getColor(): string
    {
        return $this->color();
    }

    public function isClosed(): bool
    {
        return in_array($this, [self::CANCELLED, self::REJECTED]);
    }

    public function isOpen(): bool
    {
        return ! $this->isClosed() && $this !== self::ACTIVE;
    }

    // the steps an order goes through, in order
    public static function journey(CardForm $form): array
    {
        return $form === CardForm::PHYSICAL
            ? [self::PENDING, self::APPROVED, self::PROCESSING, self::SHIPPED, self::ACTIVE]
            : [self::PENDING, self::APPROVED, self::PROCESSING, self::ACTIVE];
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])->all();
    }
}
