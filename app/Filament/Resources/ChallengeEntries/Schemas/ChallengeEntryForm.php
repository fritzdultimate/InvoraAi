<?php

namespace App\Filament\Resources\ChallengeEntries\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ChallengeEntryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('challenge_id')
                    ->required()
                    ->numeric(),
                TextInput::make('challenge_category_id')
                    ->required()
                    ->numeric(),
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('score')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('rank')
                    ->numeric(),
                DateTimePicker::make('completed_at'),
            ]);
    }
}
