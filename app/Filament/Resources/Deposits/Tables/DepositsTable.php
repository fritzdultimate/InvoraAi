<?php

namespace App\Filament\Resources\Deposits\Tables;

use App\Enums\DepositStatus;
use App\Models\Deposit;
use App\Services\DepositService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class DepositsTable
{
    public string $statusTab = 'all';
    public array $tabs = [];
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.email')
                    ->label('User')
                    ->weight('medium')
                    ->color('success')
                    ->description(fn (Deposit $record) => ucfirst($record->user->name))
                    ->sortable(),
                TextColumn::make('amount')
                    ->money('usd')
                    // ->description(fn (Deposit $record) => strtoupper($record->currency))
                    ->sortable(),
                TextColumn::make('address')
                    ->copyable()
                    ->limit(10)
                    ->copyMessage('Address copied')
                    ->copyMessageDuration(1500)
                    ->icon('heroicon-o-clipboard')
                    ->searchable(),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (DepositStatus  $state): string => $state->color())      
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('currency')
                    ->badge()
                    ->color(fn (string $state) => match (strtoupper($state)) {
                        'USD'  => 'success',
                        'NGN'  => 'warning',
                        'BTC'  => 'info',
                        'ETH'  => 'purple',
                        'USDT' => 'success',
                        default => 'gray',
                    })
                    ->searchable(),
                TextColumn::make('amount_paid')
                    ->money('usd')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc')

            ->filters([
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('cancel')
                        ->label('Cancel')
                        ->color('danger')
                        ->icon('heroicon-o-x-mark')
                        ->requiresConfirmation()
                        ->visible(fn (Deposit $record) =>
                            $record->status !== DepositStatus::FINISHED && $record->status !== DepositStatus::CANCELLED && $record->status !== DepositStatus::FAILED && $record->status !== DepositStatus::EXPIRED
                        )
                        ->action(function (Deposit $record) {
                            $record->status = DepositStatus::CANCELLED;
                            $record->save();
                        }),

                    Action::make('approve')
                        ->label('Approve')
                        ->color('success')
                        ->icon('heroicon-o-check')
                        ->requiresConfirmation()
                        ->visible(fn (Deposit $record) =>
                            $record->status !== DepositStatus::FINISHED && $record->status !== DepositStatus::CANCELLED && $record->status !== DepositStatus::FAILED && $record->status !== DepositStatus::EXPIRED
                        )
                        ->action(function (Deposit $record) {
                            DepositService::markAsFinished($record);
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
