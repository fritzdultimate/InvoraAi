<?php

namespace App\Filament\Resources\RankBonuses\Tables;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\RankBonus;
use App\Services\Wallet\WalletService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RankBonusesTable
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
                        'danger' => 'reversed',
                    ])
                    ->sortable(),

                TextColumn::make('locked_at')
                    ->label('Claimable')
                    ->since(),

                TextColumn::make('credited_at')
                    ->label('Credited')
                    ->since(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
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
                    Action::make('approve')
                        ->label('Aprrove') 
                        ->color('success')
                        ->icon('heroicon-o-check-circle')
                        ->modalHeading('Approve This Rank Bonus?')
                        ->modalDescription('This will credit the user and mark this bonus as credited.')
                        ->requiresConfirmation()
                        ->visible(fn (RankBonus $record) =>
                            $record->status === 'locked'
                        )
                        ->form([
                            Select::make('asset')
                                ->label('Select Wallet')
                                ->options([
                                    'main' => 'Main Balance',
                                    'deposit' => 'Deposit Balance',
                                    'referral_bonus' => 'Referral Bonus Balance',
                                    'locked_balance' => 'Locked Balance',
                                    'profit' => 'Profit Balance',
                                ])
                                ->required()
                                ->default('deposit'),
                        ])
                        ->action(function (RankBonus $record, array $data) {
                            try {
                                WalletService::credit(
                                    $record->user,
                                    $record->amount,
                                    LedgerReference::RANKBONUS,
                                    $record->id,
                                    "made by admin | credit rank bonus",
                                    LedgerAsset::from($data['asset'])
                                );

                                $record->status = 'credited';
                                $record->credited_at = now();
                                $record->save();

                                Notification::make()
                                    ->title('Bonus credited and status updated.')
                                    ->success()
                                    ->send();
                            } catch(Halt $e) {
                                Notification::make()
                                    ->title($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                    
                    Action::make('deny')
                        ->label('Deny') 
                        ->color('danger')
                        ->icon('heroicon-o-check-circle')
                        ->modalHeading('Deny This Rank Bonus?')
                        ->modalDescription('This will mark this bonus as denied.')
                        ->requiresConfirmation()
                        ->visible(fn (RankBonus $record) =>
                            $record->status === 'locked'
                        )
                        ->action(function (RankBonus $record) {
                            try {
                                $record->status = 'reversed';
                                $record->save();

                                Notification::make()
                                    ->title('Bonus status updated.')
                                    ->success()
                                    ->send();
                            } catch(Halt $e) {
                                Notification::make()
                                    ->title($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
