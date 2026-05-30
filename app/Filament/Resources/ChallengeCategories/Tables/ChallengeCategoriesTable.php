<?php

namespace App\Filament\Resources\ChallengeCategories\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ChallengeCategoriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('challenge.name')->label('Challenge'),
                BadgeColumn::make('type')
                    ->colors([
                        'success' => 'volume',
                        'warning' => 'referrals',
                        'danger' => 'fastest',
                    ]),
                TextColumn::make('phase')->badge(true),
                TextColumn::make('prize_pool')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('rewards'),
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
