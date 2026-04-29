<?php

namespace App\Filament\Resources\BotInvestments\Tables;

use App\Enums\BotInvestmentStatus;
use App\Models\BotInvestment;
use App\Services\Bot\BotInvestmentService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\ForceDeleteBulkAction;
use Filament\Actions\RestoreBulkAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\TrashedFilter;
use Filament\Tables\Table;

class BotInvestmentsTable {
    public static function configure(Table $table): Table
    {
        return $table
        ->modifyQueryUsing(fn ($query) => 
                $query->with(['user', 'bot'])
            )
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable(query: function ($query, $search) {
                        $query->whereHas('user', function ($q) use ($search) {
                            $q->where('name', 'like', "%{$search}%")
                            ->orWhere('email', 'like', "%{$search}%");
                        });
                    })
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->description(function ($record) {
                        $email = $record->user?->email;
                        return $email ? strtolower($email) : '—';
                    })
                    ->color('primary')
                    ->weight('semibold')
                    ->icon('heroicon-o-user')
                    ->sortable(),
                TextColumn::make('bot.name')
                    ->label('Bot')
                    ->badge()
                    ->color('info')
                    ->sortable(),

                TextColumn::make('capital')
                    ->label('Capital')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('amount')
                    ->label('Current Amount')
                    ->money('USD')
                    ->sortable(),
                TextColumn::make('total_profit')
                    ->label('Profit')
                    ->money('USD')
                    ->color('success')
                    ->sortable(),

                TextColumn::make('status')
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
                TextColumn::make('matures_at')
                    ->label('Maturity')
                    ->dateTime('M d, Y h:i A')
                    ->since()
                    ->tooltip(fn ($state) => $state?->format('F j, Y g:i A'))
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Started At')
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
                    Action::make('terminate')
                        ->label('Terminate') 
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->modalHeading('Terminate This Investment')
                        ->modalDescription('This investment will be terminated.')
                        ->requiresConfirmation()
                        ->visible(fn (BotInvestment $record) =>
                            $record->status !== BotInvestmentStatus::COMPLETED && $record->status !== BotInvestmentStatus::TERMINATED
                        )
                        ->action(function (BotInvestment $record) {
                            try {
                                BotInvestmentService::adminTerminate($record);

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
