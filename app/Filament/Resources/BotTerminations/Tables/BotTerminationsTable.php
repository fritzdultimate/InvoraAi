<?php

namespace App\Filament\Resources\BotTerminations\Tables;

use App\Enums\BotInvestmentStatus;
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
        ->modifyQueryUsing(fn ($query) => 
                $query->with(['botInvestment.user', 'botInvestment.bot'])
            )
            ->columns([
                TextColumn::make('botInvestment.user.name')
                    ->label('User')
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('botInvestment.user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                        });
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->description(function ($record) {
                        $email = $record->botInvestment?->user?->email;
                        return $email ? strtolower($email) : '—';
                    })
                    ->color('primary')
                    ->weight('semibold')
                    ->icon('heroicon-o-user')
                    ->sortable(),
                TextColumn::make('botInvestment.bot.name')
                    ->label('Bot')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('botInvestment.amount')
                    ->label('Capital')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('botInvestment.total_profit')
                    ->label('Profit')
                    ->money('USD')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('botInvestment.status')
                    ->label('Status')
                    ->badge()
                    ->formatStateUsing(fn ($state) => match($state) {
                        BotInvestmentStatus::ACTIVE => 'Active',
                        BotInvestmentStatus::COMPLETED => 'Completed',
                        BotInvestmentStatus::TERMINATED => 'Terminated',
                        BotInvestmentStatus::TERMINATIONREQUEST => 'Pending Termination',
                        default => 'Unknown',
                    })
                    ->color(fn ($state) => match($state) {
                        BotInvestmentStatus::ACTIVE => 'success',
                        BotInvestmentStatus::COMPLETED => 'info',
                        BotInvestmentStatus::TERMINATED => 'danger',
                        BotInvestmentStatus::TERMINATIONREQUEST => 'warning',
                        default => 'gray',
                    })
                    ->icon(fn ($state) => match($state) {
                        BotInvestmentStatus::ACTIVE => 'heroicon-o-play',
                        BotInvestmentStatus::COMPLETED => 'heroicon-o-check',
                        BotInvestmentStatus::TERMINATED => 'heroicon-o-x-circle',
                        BotInvestmentStatus::TERMINATIONREQUEST => 'heroicon-o-clock',
                    })
                    ->sortable(),
                TextColumn::make('penalty_percent')
                    ->label('Penalty (%)')
                    ->formatStateUsing(fn ($state) => number_format($state, 2) . '%')
                    ->color(fn ($state) => $state > 0 ? 'danger' : 'success')
                    ->tooltip('Percentage deducted as penalty')
                    ->sortable(),
                TextColumn::make('penalty_amount')
                    ->label('Penalty Amount')
                    ->money('USD')
                    ->color('danger')
                    ->weight('medium')
                    ->sortable(),
                TextColumn::make('amount_returned')
                    ->label('Settled Amount')
                    ->money('USD', true)
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
                            $record->terminated_at === null
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
