<?php

namespace App\Filament\Resources\Withdrawals\Schemas;

use App\Enums\WithdrawalStatus;
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
                TextInput::make('user_id')
                    ->required()
                    ->numeric(),
                TextInput::make('wallet_id')
                    ->required()
                    ->numeric(),
                TextInput::make('amount')
                    ->required()
                    ->numeric(),
                TextInput::make('address')
                    ->required(),
                TextInput::make('network'),
                Select::make('status')
                    ->options(WithdrawalStatus::class)
                    ->default('pending')
                    ->required(),
                TextInput::make('tx_hash'),
                Textarea::make('failure_reason')
                    ->columnSpanFull(),
                DateTimePicker::make('processed_at'),
            ]);
    }
}
