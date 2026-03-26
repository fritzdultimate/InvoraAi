<?php

namespace App\Filament\Resources\ReferralLevels\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class ReferralLevelForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('level')
                    ->numeric()
                    ->required(),

                TextInput::make('percent')
                    ->label('Percentage')
                    ->numeric()
                    ->helperText('E.g 1, 0.5, 2.5, etc')
                    ->required(),

                TextInput::make('lock_days')
                    ->numeric()
                    ->label('Lock-up Days')
                    ->required(),

                Toggle::make('is_active')
                    ->label('Active'),
                    ]);
    }
}
