<?php

namespace App\Filament\Resources\KycVerifications\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Schema;
use Filament\Support\View\Components\BadgeComponent;
use Filament\Tables\Columns\BadgeColumn;

class KycVerificationForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                // TextInput::make('user.name')
                    // ->label('User'),
                BadgeColumn::make('address')->disabled(),
                TextInput::make('country')->disabled(),
                DatePicker::make('date_of_birth')->disabled(),
                Select::make('document_type')
                    ->disabled()
                    ->options([
                        'passport' => 'Passport',
                        'national_id' => 'National ID',
                        'drivers_license' => 'Driver’s License',
                    ]),
                FileUpload::make('document_front')
                    ->disabled()
                    ->image()
                    ->disk('local')
                    ->downloadable(),
                FileUpload::make('document_back')->disabled()->image()->disk('local')->downloadable(),
                BadgeColumn::make('status')
                    ->disabled(),
                // Textarea::make('admin_note'),
                // DateTimePicker::make('reviewed_at'),
            ]);
    }

}
