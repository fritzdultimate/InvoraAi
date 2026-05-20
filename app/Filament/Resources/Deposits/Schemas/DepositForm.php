<?php

namespace App\Filament\Resources\Deposits\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;

class DepositForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->searchable()
                    ->preload()
                    ->required(),
                TextInput::make('address'),
                TextInput::make('status')
                    ->required()
                    ->default('pending'),
                TextInput::make('amount_paid') 
                    ->numeric(),
                TextInput::make('nowpayments_invoice_id'),
                DateTimePicker::make('processed_at'),
                FileUpload::make('receipt_path')
                    ->disabled()
                    ->image()
                    ->disk('local')
                    ->downloadable(),
            ]);
    }
}
