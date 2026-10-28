<?php

namespace App\Filament\Resources\PaymentSettings\Tables;

use App\Models\PaymentSetting;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentSettingsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('provider')
                    ->searchable(),
                TextColumn::make('api_key')
                    ->visible(fn () => PaymentSetting::canManageKeys()),
                TextColumn::make('ipn_secret')
                    ->visible(fn () => PaymentSetting::canManageKeys()),
                TextColumn::make('webhook_url')
                    ->searchable(),
                IconColumn::make('is_active')
                    ->boolean(),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                EditAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
