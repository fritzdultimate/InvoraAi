<?php

namespace App\Filament\Resources\Withdrawals\Tables;

use App\Enums\WithdrawalStatus;
use App\Models\Withdrawal;
use App\Services\WithdrawalService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class WithdrawalsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.email')
                    ->label('User')
                    ->weight('medium')
                    ->color('success')
                    ->description(fn (Withdrawal $record) => ucfirst($record->user->name))
                    ->sortable(),
                TextColumn::make('meta.total_to_debit')
                    ->label('Amount')
                    ->getStateUsing(fn(Withdrawal $record) => $record->meta['total_to_debit'] ?? $record->amount)
                    ->money('usd')
                    ->sortable(),
                TextColumn::make('payout')
                    ->placeholder(fn(Withdrawal $record) => $record->amount)
                    ->money('usd')
                    ->getStateUsing(fn($record) => ($record->meta['total_to_debit'] ?? $record->amount) - ($record->meta['fee'] ?? 0))
                    ->sortable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (WithdrawalStatus  $state): string => $state->color())      
                    ->searchable(),
                TextColumn::make('asset')
                    ->badge()
                    ->color('info'),
                BadgeColumn::make('address')
                    ->color('info')
                    ->copyable()
                    ->limit(10)
                    ->copyMessage('Address copied')
                    ->copyMessageDuration(1500)
                    ->icon('heroicon-o-clipboard')
                    ->searchable(),
                TextColumn::make('processed_at')
                    ->dateTime()
                    ->placeholder('_')
                    ->sortable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('cancel')
                        ->label('Cancel')
                        ->color('danger')
                        ->icon('heroicon-o-x-circle')
                        ->modalHeading('Cancel Withdrawal')
                        ->modalDescription('This withdrawal will be marked as cancelled. This action cannot be undone.')
                        ->requiresConfirmation()
                        ->visible(fn (Withdrawal $record) =>
                            $record->status !== WithdrawalStatus::COMPLETED && $record->status !== WithdrawalStatus::CANCELLED && $record->status !== WithdrawalStatus::FAILED
                        )
                        ->action(function (Withdrawal $record) {
                            $record->status = WithdrawalStatus::CANCELLED;
                            $record->save();
                        }),

                    Action::make('review')
                        ->label('Review')
                        ->color('warning')
                        ->icon('heroicon-o-eye')
                        ->modalHeading('Mark as Under Review')
                        ->modalDescription('This withdrawal will be marked as under review.')
                        ->requiresConfirmation()
                        ->visible(fn (Withdrawal $record) =>
                           $record->status === WithdrawalStatus::PENDING
                        )
                        ->action(function (Withdrawal $record) {
                            WithdrawalService::review($record);
                        }),

                    Action::make('process')
                        ->label('Process')
                        ->color('primary')
                        ->icon('heroicon-o-arrow-path')
                        ->modalHeading('Start Processing')
                        ->modalDescription('This withdrawal will be marked as processing.')
                        ->requiresConfirmation()
                        ->visible(fn (Withdrawal $record) =>
                            $record->status === WithdrawalStatus::REVIEW
                        )
                        ->action(function (Withdrawal $record) {
                            WithdrawalService::markAsProcessing($record);
                        }),

                    Action::make('fail')
                        ->label('Fail')
                        ->color('danger')
                        ->icon('heroicon-o-exclamation-triangle')
                        ->requiresConfirmation()
                        ->modalHeading('Fail Withdrawal')
                        ->modalDescription('This withdrawal will be marked as failed.')
                        ->visible(fn (Withdrawal $record) =>
                            $record->status !== WithdrawalStatus::COMPLETED && $record->status !== WithdrawalStatus::CANCELLED && $record->status !== WithdrawalStatus::FAILED
                        )
                        ->action(function (Withdrawal $record) {
                            WithdrawalService::markAsFailed($record);
                    }),

                    Action::make('approve')
                        ->label('Aprrove')
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->modalHeading('Approve Withdrawal?')
                        ->modalDescription('This withdrawal will be approved and user deducted.')
                        ->requiresConfirmation()
                        ->visible(fn (Withdrawal $record) =>
                            $record->status === WithdrawalStatus::PROCESSING
                        )
                        ->action(function (Withdrawal $record) {
                            try {
                                WithdrawalService::complete($record);

                                Notification::make()
                                    ->title('Withdrawal approved')
                                    ->success()
                                    ->send();
                            } catch(\Throwable $e) {
                                Notification::make()
                                    ->title('Withdrawal failed')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();

                                // throw $e;
                            }
                        }),
                    DeleteAction::make(),
                ])
                ->label('Action')
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
