<?php

namespace App\Filament\Resources\WithdrawalCurrencies\Schemas;

use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class WithdrawalCurrencyForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make([
                    TextInput::make('code')
                        ->required()
                        ->maxLength(10)
                        ->placeholder('ETH')
                        ->unique(ignoreRecord: true),

                    TextInput::make('name')
                        ->required()
                        ->placeholder('Ethereum')
                        ->maxLength(50),

                    Toggle::make('is_enabled')
                        ->label('Enabled')
                        ->default(true),
                ])
                ->heading('Currency Details')
                ->columns(2),

                Section::make([
                    Repeater::make('networks')
                        ->relationship()
                        ->schema([
                            TextInput::make('name')
                                ->required()
                                ->placeholder('TRC20'),

                            TextInput::make('network')
                                ->required()
                                ->placeholder('Tron'),

                            Toggle::make('is_enabled')
                                ->label('Enabled')
                                ->default(true),
                        ])
                        ->addActionLabel('Add Network')
                        ->columns(2)
                        ->collapsed(false),
                ])
                ->heading('Supported Networks')
                ->description('Add and manage blockchain networks for this currency'),
            ]);
    }
}
