<?php

namespace App\Filament\Resources\Ranks\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class RankForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('level')
                    ->required()
                    ->numeric(),
                TextInput::make('required_volume')
                    ->required()
                    ->numeric(),
                TextInput::make('required_active_referrals')
                    ->required()
                    ->numeric(),
                TextInput::make('required_earnings')
                    ->required()
                    ->numeric(),
                TextInput::make('direct_referrals')
                    ->required()
                    ->numeric(),
                TextInput::make('bonus')
                    ->label('Bonus Amount')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->minValue(0)
                    ->step(0.01),
                TextInput::make('deposits')
                    ->label('Required Deposits')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->minValue(0)
                    ->step(10),
                Toggle::make('global_pool_share')
                    // ->label('Glob')
                    ->required(),
                Toggle::make('global_override')
                ->label('Global Override')
                ->required()
            ]);
    }
}
