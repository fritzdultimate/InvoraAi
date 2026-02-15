<?php

namespace App\Filament\Resources\Bots\Schemas;

use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BotForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Basic Information')
                ->schema([

                    TextInput::make('name')
                        ->required()
                        ->maxLength(255)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) =>
                            $set('slug', \Str::slug($state))
                        ),

                    TextInput::make('slug')
                        ->required()
                        ->unique(ignoreRecord: true),

                    Textarea::make('description')
                        ->columnSpanFull(),

                    Toggle::make('is_active')
                        ->label('Active Bot')
                        ->default(true),

                    ])->columns(2),

                    Section::make('Investment Settings')
                    ->schema([
                        TextInput::make('price')
                            ->numeric()
                            ->required()
                            ->prefix('$'),

                        TextInput::make('min_amount')
                            ->numeric()
                            ->required()
                            ->prefix('$'),

                        TextInput::make('max_amount')
                            ->numeric()
                            ->required()
                            ->prefix('$'),

                        TextInput::make('daily_return_percent')
                            ->numeric()
                            ->required()
                            ->suffix('%'),

                        TextInput::make('early_withdrawal_penalty_percent')
                            ->numeric()
                            ->required()
                            ->suffix('%'),

                    ]),

                    Section::make('Timing Configuration')
                    ->schema([

                        TextInput::make('license_duration_days')
                            ->numeric()
                            ->required()
                            ->suffix('Days'),

                        TextInput::make('lock_days')
                            ->numeric()
                            ->required()
                            ->suffix('Days'),

                        TextInput::make('payout_interval_hours')
                            ->numeric()
                            ->required()
                            ->suffix('Hours'),

                    ])->columns(3),
            ]);
    }
}
