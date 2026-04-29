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
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
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

                    Action::make('stop')
                        ->label('Stop') 
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->modalHeading('Stop This Investment')
                        ->modalDescription('This investment will be mark as completed.')
                        ->requiresConfirmation()
                        ->form([

                            Select::make('action_type')
                                ->label('Action Type')
                                ->options([
                                    'silent' => 'Silent Stop (No balance change)',
                                    'refund' => 'Refund from Locked Balance',
                                    'custom' => 'Custom Debit/Credit',
                                ])
                                ->required()
                                ->live(),

                            Select::make('wallet_type')
                                ->label('Select Wallet')
                                ->options([
                                    'main' => 'Main Balance',
                                    'deposit' => 'Deposit Balance',
                                    'referral_bonus' => 'Referral Bonus Balance',
                                    'locked_balance' => 'Locked Balance',
                                    'profit' => 'Profit Balance',
                                ])
                                ->visible(fn ($get) => in_array($get('action_type'), ['refund', 'custom']))
                                ->required(fn ($get) => in_array($get('action_type'), ['refund', 'custom'])),

                            TextInput::make('amount')
                                ->label('Amount')
                                ->numeric()
                                ->placeholder('Leave empty to use investment amount')
                                ->visible(fn ($get) => $get('action_type') === 'custom'),

                            Toggle::make('debit_locked')
                                ->label('Debit from Locked Balance')
                                ->default(true)
                                ->visible(fn ($get) => $get('action_type') !== 'silent'),

                        ])
                        ->visible(fn (BotInvestment $record) =>
                            $record->status !== BotInvestmentStatus::COMPLETED && $record->status !== BotInvestmentStatus::TERMINATED
                        )
                        ->action(function (BotInvestment $record, array $data) {
                            try {
                                $amount = $data['amount'] ?? $record->capital;

                                switch ($data['action_type']) {
                                    case 'silent':
                                    $record->update([
                                        'status' => 'completed',
                                        'matures_at' => now()
                                    ]);
                                    $record->save();
                                    break;

                                    case 'refund':
                                    BotInvestmentService::refundToWallet(
                                        investment: $record,
                                        walletType: $data['wallet_type'],
                                        amount: $record->amount
                                    );
                                    break;

                                    case 'custom':
                                    BotInvestmentService::customAdjustment(
                                        investment: $record,
                                        walletType: $data['wallet_type'],
                                        amount: $amount,
                                        debitLocked: $data['debit_locked']
                                    );
                                    break;
                                }

                                Notification::make()
                                ->title('Investment processed successfully')
                                ->success()
                                ->send();
                            } catch(\Throwable $e) {
                                Notification::make()
                                    ->title('Action failed')
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
