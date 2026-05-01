<?php

namespace App\Filament\Resources\TradingAssets\Schemas;

use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class TradingAssetForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('symbol')
                        ->required()
                        ->maxLength(10)
                        ->placeholder('ETH')
                        ->unique(ignoreRecord: true),

                    TextInput::make('name')
                        ->required()
                        ->placeholder('Ethereum')
                        ->maxLength(50),

                    Toggle::make('active')
                        ->label('Active')
                        ->default(true),
            ]);
    }
}
