<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email()
                    ->required(),
                DateTimePicker::make('email_verified_at'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Textarea::make('two_factor_secret')
                    ->columnSpanFull(),
                Textarea::make('two_factor_recovery_codes')
                    ->columnSpanFull(),
                DateTimePicker::make('two_factor_confirmed_at'),
                TextInput::make('firstname'),
                TextInput::make('lastname'),
                TextInput::make('balance')
                    ->required()
                    ->numeric()
                    ->default(0.0),
                TextInput::make('phone_number')
                    ->tel(),
                TextInput::make('country'),
                TextInput::make('timezone'),
                TextInput::make('affiliate_code'),
                TextInput::make('referrer_id')
                    ->numeric(),
                Select::make('kyc_status')
                    ->options([
            'pending' => 'Pending',
            'unsubmitted' => 'Unsubmitted',
            'approved' => 'Approved',
            'rejected' => 'Rejected',
        ])
                    ->default('unsubmitted')
                    ->required(),
                DateTimePicker::make('kyc_submitted_at'),
                Toggle::make('two_factor_enable')
                    ->required(),
                DateTimePicker::make('blocked_at'),
                DateTimePicker::make('suspended_at'),
                DateTimePicker::make('lock_roi_at'),
            ]);
    }
}
