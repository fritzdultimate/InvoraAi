<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;

enum CardForm: string implements HasLabel
{
    case PHYSICAL = 'physical';
    case VIRTUAL = 'virtual';

    public function label(): string
    {
        return match ($this) {
            self::PHYSICAL => 'Physical card',
            self::VIRTUAL => 'Virtual card',
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
