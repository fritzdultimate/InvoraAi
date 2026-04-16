<?php

namespace App\Filament\Resources\DailyResidualBonuses\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class DailyResidualBonusesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->formatStateUsing(fn ($state) => ucwords($state))
                    ->description(fn ($record) => $record->user?->email)
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user')
                    ->copyable()
                    ->copyMessage('Email copied')
                    ->copyMessageDuration(1500),

                TextColumn::make('user.rank.rank.name')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('USD', true)
                    ->sortable()
                    ->weight('bold')
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger')
                    ->icon(fn ($state) => $state > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down'),
                BadgeColumn::make('status')
                    ->colors([
                        'success' => 'credited',
                        'warning' => 'locked',
                        'danger' => 'denied',
                    ])
                    ->sortable(),
                TextColumn::make('locked_at')
                    ->label('Claimable')
                    ->since(),

                TextColumn::make('credited_at')
                    ->label('Credited')
                    ->since(),
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
