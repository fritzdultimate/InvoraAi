<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Services\BalanceService;
use App\Services\Wallet\WalletService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;

class UsersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                // 👤 USER IDENTITY
                TextColumn::make('name')
                    ->label('User')
                    ->description(fn ($record) => $record->email)
                    ->searchable(['name', 'email'])
                    ->weight('medium')
                    ->color('info')
                    ->icon('heroicon-o-user'),
                TextColumn::make('main_balance')
                    ->money('usd', 0, null, 2)
                    ->sortable()
                    ->weight('bold')
                    ->color('success'),

                IconColumn::make('email_verified_at')
                    ->label('Email')
                    ->boolean()
                    ->getStateUsing(fn ($record) => filled($record->email_verified_at))
                    ->trueIcon('heroicon-o-check-badge')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                IconColumn::make('is_admin')
                    ->label('Admin')
                    ->boolean()
                    ->getStateUsing(fn ($record) => $record->hasRole('admin'))
                    ->trueIcon('heroicon-o-shield-check')
                    ->falseIcon('heroicon-o-x-circle')
                    ->trueColor('success')
                    ->falseColor('danger'),
                // IconColumn::make('lock_roi_at')
                //     ->label('ROI Lock')
                //     ->boolean()
                //     ->getStateUsing(fn ($record) => filled($record->lock_roi_at))
                //     ->trueIcon('heroicon-o-lock-closed')
                //     ->falseIcon('heroicon-o-lock-open')
                //     ->trueColor('danger')
                //     ->falseColor('success'),

                IconColumn::make('suspended_at')
                    ->label('Suspended')
                    ->boolean()
                    ->getStateUsing(fn ($record) => filled($record->suspended_at))
                    ->trueColor('danger')
                    ->falseColor('success'),
                IconColumn::make('is_leader')
                    ->label('Leader')
                    ->boolean()
                    ->state(fn ($record) => $record->hasRole('leader'))
                    ->trueColor('success')
                    ->falseColor('danger'),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
                TextColumn::make('referredBy.name')
                    ->label('Referrer')
                    ->description(fn ($record) => $record->referredBy?->email ?? 'N/A')
                    ->icon('heroicon-o-user')
                    ->color('success')
                    ->weight('medium')
                    ->searchable(['referredBy.name', 'referredBy.email'])
                    ->sortable(),
                TextColumn::make('rank_display')
                    ->label('Rank')
                    ->getStateUsing(function ($record) {
                        return $record->rank?->rank?->name ?? 'Unranked';
                    })
                    ->description(fn ($record) => 
                        $record->rank?->rank 
                            ? 'Level ' . $record->rank->rank->level 
                            : 'No level yet'
                    )
                    ->icon('heroicon-o-trophy')
                    ->color(fn ($state) => $state ? 'success' : 'gray')
                    ->weight('medium')
                    ->sortable()
            ])
            ->defaultSort('created_at', 'desc')
            ->filters([
                //
            ])
            ->recordActions([
                ActionGroup::make([
                    Action::make('impersonate')
                        ->label('Login as user')
                        ->icon('heroicon-o-arrow-right-on-rectangle')
                        ->color('warning')
                        ->requiresConfirmation()
                        // ->visible(fn () => auth()->user()?->isAdmin())
                        ->action(function ($record) {

                            abort_unless(auth()->user()->isAdmin(), 403);

                            // Prevent impersonating other admins
                            if ($record->isAdmin()) {
                                throw new \Exception('You cannot impersonate another admin.');
                            }

                            session([
                                'impersonator_id' => auth()->id(),
                            ]);

                            Auth::login($record);
                            session()->regenerate();

                            return redirect('/dashboard');
                        }),


                    
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
                                    'locked_balance' => 'Locked Balance',
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
                    // ->visible(fn () => auth()->user()->hasRole(['super-admin'])),

                    Action::make('debit')
                        ->label('Debit')
                        ->icon('heroicon-o-minus-circle')
                        ->color('danger')
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
                                    'locked_balance' => 'Locked Balance',
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
                            WalletService::debit(
                                $record,
                                $data['amount'],
                                LedgerReference::WITHDRAWAL,
                                auth()->id(),
                                "made by admin | " . $data['description'],
                                LedgerAsset::from($data['asset'])
                            );

                            Notification::make()
                                ->title('Balance Updated')
                                ->success()
                                ->send();
                        }),
                    // ->visible(fn () => auth()->user()->hasRole(['super-admin'])),


                    // Make Leader
                    Action::make('makeLeader')
                        ->label('Make Leader')
                        ->icon('heroicon-o-shield-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => !$record->hasRole('leader'))
                        ->action(function ($record) {

                            abort_unless(!$record->hasRole('leader'), 403);

                            $record->assignRole('leader');

                            Notification::make()
                                ->title('Leader role assigned')
                                ->body('This user’s new stakes will no longer trigger downline bonuses.')
                                ->success()
                                ->send();
                        })
                        ->modalHeading('Confirm role change')
                        ->modalDescription('Are you sure you want to change this user’s leadership status?'),
                    // end make leaader

                    // Remove Leader
                    Action::make('removeLeader')
                        ->label('Remove Leader Role')
                        ->icon('heroicon-o-shield-exclamation')
                        ->color('warning')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->hasRole('leader'))
                        ->action(function ($record) {

                            abort_unless($record->hasRole('leader'), 403);

                            $record->removeRole('leader');

                            Notification::make()
                                ->title('Leader role removed')
                                ->body('This user’s new stakes will now trigger downline bonuses.')
                                ->success()
                                ->send();
                        })
                        ->modalHeading('Confirm role change')
                        ->modalDescription('Are you sure you want to change this user’s leadership status?'),
                    // end remove leaader

                    // Suspending action
                    Action::make('suspendUser')
                        ->label('Suspend User')
                        ->icon('heroicon-o-no-symbol')
                        ->color('danger')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => ! $record->suspended_at)
                        ->action(function ($record) {

                            abort_unless(! $record->suspended_at, 403);

                            $record->update([
                                'suspended_at' => now(),
                            ]);

                            Notification::make()
                                ->title('User Suspended')
                                ->body('This user has been suspended and can no longer perform restricted actions.')
                                ->danger()
                                ->send();
                        })
                        ->modalHeading('Suspend User')
                        ->modalDescription('Are you sure you want to suspend this user? This action can be reversed.'),

                    // Unsuspending user action
                    Action::make('unsuspendUser')
                        ->label('Unsuspend User')
                        ->icon('heroicon-o-check-circle')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => $record->suspended_at)
                        ->action(function ($record) {

                            abort_unless($record->suspended_at, 403);

                            $record->update([
                                'suspended_at' => null,
                            ]);

                            Notification::make()
                                ->title('User Unsuspended')
                                ->body('This user now has full access to the platform again.')
                                ->success()
                                ->send();
                        })
                        ->modalHeading('Unsuspend User')
                        ->modalDescription('Are you sure you want to restore this user’s access?'),

                    // ActionGroup::make([
                        Action::make('makeAdmin')
                            ->label('Make Admin')
                            ->icon('heroicon-o-shield-check')
                            ->color('success')
                            ->requiresConfirmation()
                            ->visible(fn ($record) => !$record->hasRole('admin'))
                            ->action(function ($record) {

                                abort_unless(!$record->hasRole('admin'), 403);

                                // remove lower roles if needed
                                $record->removeRole('user');

                                $record->assignRole('admin');

                                Notification::make()
                                    ->title('Admin role assigned')
                                    ->body('This user now has full administrative privileges.')
                                    ->success()
                                    ->send();
                            })
                            ->modalHeading('Promote to Admin')
                            ->modalDescription('This will grant full system control. Proceed with caution.'),
                            // ->visible(fn ($record) => auth()->user()->hasRole('admin')),

                        Action::make('makeUser')
                            ->label('Downgrade to User')
                            ->icon('heroicon-o-user')
                            ->color('danger')
                            ->requiresConfirmation()
                            ->visible(fn ($record) => $record->hasRole('admin'))
                            ->action(function ($record) {

                                abort_unless($record->hasRole('admin'), 403);

                                $record->removeRole('admin');

                                $record->assignRole('user');

                                Notification::make()
                                    ->title('User downgraded')
                                    ->body('Administrative access has been revoked.')
                                    ->warning()
                                    ->send();
                            })
                            ->modalHeading('Revoke Admin Access')
                            ->modalDescription('This will remove all admin privileges from this user.')
                            // ->visible(fn ($record) => auth()->user()->hasRole('admin'))
                    // ])



                ]),
                
                
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
