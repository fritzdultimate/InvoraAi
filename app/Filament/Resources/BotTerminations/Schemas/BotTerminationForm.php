<?php

namespace App\Filament\Resources\BotTerminations\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class BotTerminationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('bot_investment_id')
                    ->required()
                    ->numeric(),
                TextInput::make('penalty_percent')
                    ->required()
                    ->numeric(),
                TextInput::make('penalty_amount')
                    ->required()
                    ->numeric(),
                TextInput::make('amount_returned')
                    ->required()
                    ->numeric(),
                DateTimePicker::make('terminated_at')
                    ->required(),
            ]);
    }
}
