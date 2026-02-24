<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Services\Wallet\WalletService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('email')
                    ->label('Email address')
                    ->searchable(),
                TextColumn::make('email_verified_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('two_factor_confirmed_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('firstname')
                    ->searchable(),
                TextColumn::make('lastname')
                    ->searchable(),
                TextColumn::make('balance')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('phone_number')
                    ->searchable(),
                TextColumn::make('country')
                    ->searchable(),
                TextColumn::make('timezone')
                    ->searchable(),
                TextColumn::make('affiliate_code')
                    ->searchable(),
                TextColumn::make('referrer_id')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('kyc_status')
                    ->badge(),
                TextColumn::make('kyc_submitted_at')
                    ->dateTime()
                    ->sortable(),
                IconColumn::make('two_factor_enable')
                    ->boolean(),
                TextColumn::make('blocked_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('suspended_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('lock_roi_at')
                    ->dateTime()
                    ->sortable(),
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
                    Action::make('topup')
                    ->label('Top Up')
                    ->icon('heroicon-o-plus-circle')
                    ->color('success')
                    ->form([
                        TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->minValue(0.0001),

                        Select::make('asset')
                            ->label('Select Wallet')
                            ->options([
                                'main' => 'Main Balance',
                                'deposit' => 'Deposit Balance',
                                'referral_bonus' => 'Referral Bonus Balance',
                                'locked_balance' => 'Profit Balance',
                                'profit' => 'Profit Balance',
                            ])
                            ->required()
                            ->default('deposit'),

                        Textarea::make('description')
                            ->required()
                            ->label('Reason'),
                    ])
                    ->requiresConfirmation()
                    ->action(function ($record, array $data) {
                        WalletService::credit(
                            $record,
                            $data['amount'],
                            LedgerReference::DEPOSIT,
                            auth()->id(),
                            "made by admin | " . $data['description'],
                            LedgerAsset::from($data['asset'])
                        );

                        Notification::make()
                            ->title('Balance Updated')
                            ->success()
                            ->send();
                    }),
                    EditAction::make(),
                ])
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
