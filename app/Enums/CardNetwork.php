<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CardNetwork: string implements HasLabel
{
    case VISA = 'visa';
    case MASTERCARD = 'mastercard';

    public function label(): string
    {
        return match ($this) {
            self::VISA => 'Visa',
            self::MASTERCARD => 'Mastercard',
        };
    }

    public function getLabel(): string
    {
        return $this->label();
    }

    public static function options(): array
    {
        return collect(self::cases())->mapWithKeys(fn ($case) => [$case->value => $case->label()])->all();
    }
}
