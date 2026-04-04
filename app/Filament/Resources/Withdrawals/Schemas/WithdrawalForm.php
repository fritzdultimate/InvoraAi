<?php

namespace App\Filament\Resources\Withdrawals\Schemas;

use App\Enums\WithdrawalStatus;
use App\Models\WithdrawalNetwork;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class WithdrawalForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'email')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->prefix('$')
                    ->minValue(1),
                Select::make('withdrawal_currency_id')
                    ->label('Currency')
                    ->relationship('currency', 'code')
                    ->searchable()
                    ->preload()
                    ->required()
                    ->reactive(),
                Select::make('withdrawal_network_id')
                    ->label('Network')
                    ->options(function (callable $get) {
                        $currencyId = $get('withdrawal_currency_id');

                        if (!$currencyId) return [];

                        return WithdrawalNetwork::where('withdrawal_currency_id', $currencyId)
                            ->pluck('name', 'id');
                    })
                    ->searchable()
                    ->required(),
                TextInput::make('address')
                    ->required()
                    ->maxLength(255)
                    ->placeholder('Wallet address'),
                Select::make('status')
                    ->options(WithdrawalStatus::class)
                    ->default('pending')
                    ->required(),
                TextInput::make('tx_hash')
                    ->label('Transaction Hash')
                    ->copyable()
                    ->maxLength(255),
                Textarea::make('failure_reason')
                    ->label('Failure Reason')
                    ->visible(fn ($get) => $get('status') === 'failed')
                    ->columnSpanFull(),
                DateTimePicker::make('processed_at'),
            ]);
    }
}
