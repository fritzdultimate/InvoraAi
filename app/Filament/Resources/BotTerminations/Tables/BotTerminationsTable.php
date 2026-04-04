<?php

namespace App\Filament\Resources\BotTerminations\Tables;

use App\Models\BotTermination;
use App\Services\Bot\BotInvestmentService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BotTerminationsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('penalty_percent')
                    ->label('Penalty (%)')
                    ->formatStateUsing(fn ($state) => number_format($state, 2) . '%')
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                    ->tooltip('Percentage deducted as penalty')
                    ->sortable(),
                TextColumn::make('penalty_amount')
                    ->label('Penalty Amount')
                    ->money('NGN', true)
                    ->color('danger')
                    ->weight('medium')
                    ->sortable(),
                TextColumn::make('amount_returned')
                    ->label('Settled Amount')
                    ->money('NGN', true)
                    ->color('success')
                    ->weight('bold')
                    ->description(fn ($record) => 'After penalties deducted')
                    ->sortable(),
                TextColumn::make('terminated_at')
                    ->label('Terminated')
                    ->dateTime('M d, Y h:i A')
                    ->since()
                    ->tooltip(fn ($state) => $state?->format('F j, Y g:i A'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Requested At')
                    ->dateTime('M d, Y h:i A')
                    ->since()
                    ->tooltip(fn ($state) => $state?->format('F j, Y g:i A'))
                    ->sortable(),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('deleted_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                TrashedFilter::make(),
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('approve')
                        ->label('Aprrove') 
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->modalHeading('Approve This Termination?')
                        ->modalDescription('This investment will be terminated.')
                        ->requiresConfirmation()
                        ->visible(fn (BotTermination $record) =>
                            $record->terminated_at !== null
                        )
                        ->action(function (BotTermination $record) {
                            try {
                                BotInvestmentService::terminate($record);

                                Notification::make()
                                    ->title('Investment terminated')
                                    ->success()
                                    ->send();
                            } catch(\Throwable $e) {
                                Notification::make()
                                    ->title('Termination failed')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();

                                // throw $e;
                            }
                        }),
                    DeleteAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                    ForceDeleteBulkAction::make(),
                    RestoreBulkAction::make(),
                ]),
            ]);
    }
}
