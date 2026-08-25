<?php

namespace App\Filament\Resources\BotInvestments\Schemas;

use App\Enums\BotInvestmentStatus;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class BotInvestmentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Assignment')
                    ->description('Which bot and license this investment runs on. The investor cannot be changed here.')
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('bot_id')
                                ->label('Bot')
                                ->relationship('bot', 'name')
                                ->searchable()
                                ->preload()
                                ->required(),

                            // bot_licenses has no "name" column, so the label is built
                            // from whatever identifying attribute the record actually has.
                            // Swap the fallback chain below for the real column(s) once
                            // confirmed (e.g. 'license_key', 'code', 'reference').
                            Select::make('bot_license_id')
                                ->label('Bot License')
                                ->relationship('botLicense')
                                ->getOptionLabelFromRecordUsing(fn ($record) =>
                                    $record->license_key
                                        ?? $record->code
                                        ?? $record->key
                                        ?? $record->reference
                                        ?? "License #{$record->id}"
                                )
                                ->preload()
                                ->nullable(),
                        ]),
                    ]),

                Section::make('Status & Schedule')
                    ->description(
                        'Changing status here does not move money or write a termination/profit record. '
                        . 'Use the Terminate / Stop row actions instead when a balance change is also needed — '
                        . 'reserve this form for corrections (e.g. fixing a wrong maturity date).'
                    )
                    ->schema([
                        Grid::make(2)->schema([
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    BotInvestmentStatus::ACTIVE->value => 'Active',
                                    BotInvestmentStatus::COMPLETED->value => 'Completed',
                                    BotInvestmentStatus::TERMINATED->value => 'Terminated',
                                    BotInvestmentStatus::TERMINATIONREQUEST->value => 'Pending Termination',
                                ])
                                ->required()
                                ->native(false),

                            Toggle::make('is_early_terminated')
                                ->label('Early Terminated')
                                ->inline(false),
                        ]),

                        Grid::make(3)->schema([
                            DateTimePicker::make('started_at')
                                ->label('Started At')
                                ->seconds(false)
                                ->native(false)
                                ->required(),

                            DateTimePicker::make('matures_at')
                                ->label('Matures At')
                                ->seconds(false)
                                ->native(false)
                                ->required(),

                            DateTimePicker::make('next_cycle_at')
                                ->label('Next Cycle At')
                                ->seconds(false)
                                ->native(false),
                        ]),
                    ]),

                Section::make('Identifiers')
                    ->description('Read-only reference values, set automatically when the investment was created.')
                    ->schema([
                        Grid::make(2)->schema([
                            TextInput::make('code')
                                ->label('Code')
                                ->disabled()
                                ->dehydrated(false),

                            TextInput::make('uuid')
                                ->label('UUID')
                                ->disabled()
                                ->dehydrated(false),
                        ]),
                    ]),
            ]);
    }
}