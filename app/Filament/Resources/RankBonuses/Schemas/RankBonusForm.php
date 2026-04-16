<?php

namespace App\Filament\Resources\RankBonuses\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class RankBonusForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('rank_id')
                    ->required()
                    ->numeric(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('title'),
                TextInput::make('description'),
                Select::make('status')
                    ->options(['credited' => 'Credited', 'locked' => 'Locked', 'reversed' => 'Reversed'])
                    ->default('credited')
                    ->required(),
                DateTimePicker::make('credited_at'),
                DateTimePicker::make('locked_at'),
            ]);
    }
}
