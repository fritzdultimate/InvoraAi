<?php

namespace App\Filament\Resources\ReferralBonuses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ReferralBonusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('referred_by_id')
                    ->numeric(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('from_user_id')
                    ->numeric(),
                TextInput::make('level')
                    ->required()
                    ->numeric()
                    ->default(1),
                TextInput::make('percent')
                    ->required()
                    ->numeric(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('bot_investment_id')
                    ->numeric(),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('meta'),
                DateTimePicker::make('calculated_for'),
                DateTimePicker::make('claimable_at'),
                DateTimePicker::make('claimed_at'),
            ]);
    }
}
