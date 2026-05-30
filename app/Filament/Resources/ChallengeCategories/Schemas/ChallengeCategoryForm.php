<?php

namespace App\Filament\Resources\ChallengeCategories\Schemas;

use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class ChallengeCategoryForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('challenge_id')
                    ->relationship('challenge', 'name')
                    ->required(),
                Select::make('type')
                    ->options([
                        'team_volume' => 'Team Volume',
                        'level_1_volume' => 'Level One Volume',
                        'personal_volume' => 'Personal Volume',
                        'activation_count' => 'Activation Count'
                    ])
                    ->required(),
                TextInput::make('phase')
                    ->required()
                    ->numeric(), 

                TextInput::make('rewards')
                    ->label('Rewards JSON')
                    ->default('{"1":2500,"2":1500,"3":1000}')
                    ->required(),
                TextInput::make('prize_pool')
                    ->required()
                    ->numeric(),
                TextInput::make('min_activation_amount')
                    ->numeric()
                    // ->label('Minimum Amount (for fastest only)'),
            ]);
    }
}
