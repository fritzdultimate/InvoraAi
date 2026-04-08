<?php

namespace App\Filament\Resources\ReferralBonuses\Tables;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\ReferralBonus;
use App\Services\Wallet\WalletService;
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
use Illuminate\Support\Facades\DB;

class ReferralBonusesTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('user.name')
                    ->label('Receiver')
                    ->formatStateUsing(fn ($state) => ucwords($state))
                    ->description(fn ($record) => $record->user?->email)
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-user')
                    ->copyable()
                    ->copyMessage('Email copied')
                    ->copyMessageDuration(1500),
                TextColumn::make('fromUser.name')
                    ->label('From User')
                    ->formatStateUsing(fn ($state) => ucwords($state))
                    ->description(fn ($record) => 'Level ' . $record->level)
                    ->searchable()
                    ->sortable()
                    ->icon('heroicon-o-arrow-uturn-left')
                    ->color('gray'),
                TextColumn::make('level')
                    ->badge()
                    ->color('info')
                    ->sortable(),
                TextColumn::make('percent')
                    ->label('Bonus %')
                    ->formatStateUsing(fn ($state) => $state . '%')
                    ->sortable(),
                TextColumn::make('amount')
                    ->label('Amount')
                    ->money('USD', true)
                    ->sortable()
                    ->weight('bold')
                    ->color(fn ($state) => $state > 0 ? 'success' : 'danger')
                    ->icon(fn ($state) => $state > 0 ? 'heroicon-o-arrow-trending-up' : 'heroicon-o-arrow-trending-down')
                    ->description(fn ($record) => $record->percent . '% bonus'),
                 BadgeColumn::make('status')
                    ->colors([
                        'success' => 'claimed',
                        'warning' => 'pending',
                        'danger' => 'failed',
                    ])
                    ->sortable(),
                TextColumn::make('claimable_at')
                    ->label('Claimable')
                    ->since(),

                TextColumn::make('claimed_at')
                    ->label('Claimed')
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
                        ->modalHeading('Approve This Referral Bonus?')
                        ->modalDescription('This will credit the user and mark this bonus as paid.')
                        ->requiresConfirmation()
                        ->visible(fn (ReferralBonus $record) =>
                            $record->status === 'pending'
                        )
                        ->action(function (ReferralBonus $record) {
                            try {
                                
                            DB::transaction(function () use ($record) {

                                if ($record->status !== 'pending') {
                                    throw new \Exception('This bonus has already been processed.');
                                }

                                $user = $record->user;

                                if (!$user) {
                                    throw new \Exception('User not found.');
                                }

                                $bonus = ReferralBonus::where('id', $record->id)
                                    ->lockForUpdate()
                                    ->firstOrFail();

                                if (!$bonus->isClaimable()) {
                                    return;
                                }

                                $bonus->update([
                                    'status' => 'claimed',
                                    'claimed_at' => now(),
                                ]);

                                WalletService::credit(
                                    $record->user,
                                    $bonus->amount,
                                    LedgerReference::ReferralBonus,
                                    $bonus->id,
                                    'credited referral bonus',
                                    LedgerAsset::REFERRALBONUS
                                );
                            });

                                Notification::make()
                                    ->title('Bonus Approved')
                                    ->body('User has been credited successfully.')
                                    ->success()
                                    ->send();
                            } catch(\Throwable $e) {
                                Notification::make()
                                   ->title('Approval Failed')
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
                ]),
            ]);
    }
}
