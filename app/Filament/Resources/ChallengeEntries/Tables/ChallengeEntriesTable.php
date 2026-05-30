<?php

namespace App\Filament\Resources\ChallengeEntries\Tables;

use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class ChallengeEntriesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('rank')
            ->columns([
                TextColumn::make('rank')
                    ->label('#')
                    ->alignCenter()
                    ->sortable()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        1 => '🥇 1',
                        2 => '🥈 2',
                        3 => '🥉 3',
                        default => $state,
                    })
                    ->color(fn ($state) => match ($state) {
                        1 => 'success',
                        2 => 'warning',
                        3 => 'danger',
                        default => 'gray',
                    })
                    ->weight('bold'),
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->weight('semibold'),
                TextColumn::make('category.type')
                    ->label('Category')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match ($state) {
                        'volume' => 'Top Volume',
                        'new_members' => 'New Members',
                        'fastest' => 'Fastest 7',
                        default => ucfirst($state),
                    })
                    ->color(fn ($state) => match ($state) {
                        'volume' => 'success',
                        'new_members' => 'warning',
                        'fastest' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('score')
                    ->label('Score')
                    ->sortable()
                    ->formatStateUsing(function ($state, $record) {

                        // 💰 Format based on category
                        if ($record->category?->type === 'volume') {
                            return '$' . number_format($state);
                        }

                        return number_format($state);
                    })
                    ->weight('bold'),
                TextColumn::make('completed_at')
                    ->label('Completed')
                    ->dateTime('M d, H:i')
                    ->sortable()
                    ->placeholder('—')
                    ->color('gray'),
            ])
            ->filters([
                SelectFilter::make('challenge_category_id')
                    ->relationship('category', 'type')
                    ->label('Category')
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
