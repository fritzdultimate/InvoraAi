<?php

namespace App\Filament\Resources\Ranks\Tables;

use App\Models\Rank;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RanksTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->label('Rank')
                    ->weight('bold')
                    ->icon('heroicon-o-star')
                    ->color('primary')
                    ->searchable(),
                BadgeColumn::make('level')
                    ->colors([
                        'gray' => fn ($state) => $state <= 1,
                        'info' => fn ($state) => $state >= 2 && $state <= 3,
                        'warning' => fn ($state) => $state >= 4 && $state <= 6,
                        'success' => fn ($state) => $state >= 7,
                    ])
                    ->sortable(),
                TextColumn::make('bonus')
                    ->label('Bonus')
                    ->numeric()
                    ->money('USD')
                    ->sortable()
                    ->icon('heroicon-o-bolt')
                    ->color('success'),
                TextColumn::make('deposits')
                    ->label('Deposits Required')
                    ->numeric()
                    ->money('USD')
                    ->sortable()
                    ->icon('heroicon-o-bolt')
                    ->color('success'),
                TextColumn::make('direct_referrals')
                    ->label('Direct Referrals')
                    ->numeric()
                    ->sortable()
                    ->icon('heroicon-o-chart-bar')
                    ->color('success'),
                IconColumn::make('global_pool_share')
                    ->label('Global Pool Share')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                IconColumn::make('global_override')
                    ->label('Global Override')
                    ->boolean()
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('required_volume')
                    ->label('Required Volume')
                    ->numeric()
                    ->money('USD')
                    ->sortable()
                    ->icon('heroicon-o-chart-bar')
                    ->color('success'),

                TextColumn::make(name: 'required_active_referrals')
                    ->label('Active Referrals')
                    ->numeric()
                    ->sortable()
                    ->icon('heroicon-o-users')
                    ->alignCenter(),

                TextColumn::make('required_earnings')
                    ->label('Required Earnings')
                    ->numeric()
                    ->money('USD')
                    ->sortable()
                    ->icon('heroicon-o-banknotes')
                    ->color('warning'),

                TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->since()
                    ->sortable()
                    ->color('gray'),

                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    EditAction::make()
                        ->label('Edit')
                        ->icon('heroicon-o-pencil-square')
                        ->color('primary'),


                    Action::make('duplicate')
                        ->label('Duplicate')
                        ->icon('heroicon-o-document-duplicate')
                        ->color('gray')
                        ->requiresConfirmation()
                        ->modalHeading('Duplicate Rank')
                        ->modalDescription('A copy of this rank will be created.')
                        ->action(function (Rank $record) {
                            $new = $record->replicate();
                            $new->name = $record->name . ' Copy';
                            $new->save();
                        }),


                ])


            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ])
            ->striped()
             ->defaultSort('level', 'asc');
    }
}
