<?php

namespace App\Filament\Resources\Deposits\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DepositForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('user_id')
                    ->numeric(),
                TextInput::make('amount')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('note'),
                TextInput::make('address'),
                TextInput::make('tx_hash'),
                TextInput::make('confirmations')
                    ->required()
                    ->numeric()
                    ->default(0),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                Textarea::make('meta')
                    ->columnSpanFull(),
                DateTimePicker::make('received_at'),
                DateTimePicker::make('confirmed_at'),
                TextInput::make('wallet_id')
                    ->numeric(),
                TextInput::make('currency')
                    ->required(),
                TextInput::make('amount_paid') 
                    ->numeric(),
                TextInput::make('nowpayments_invoice_id'),
                TextInput::make('tx_id'),
                DateTimePicker::make('processed_at'),
            ]);
    }
}
