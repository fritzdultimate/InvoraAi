<?php

namespace App\Filament\Resources\Users\Tables;

use App\Enums\BotLicenseStatus;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\User;
use App\Services\BalanceService;
use App\Services\ReferralReassignmentService;
use App\Services\Wallet\WalletService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Placeholder;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Facades\Auth;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Support\Exceptions\Halt;
use InvalidArgumentException;

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
                    // ->searchable(['referredBy.name', 'referredBy.email'])
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
                                    'matching_deposit_bonus' => 'Matching Deposit Bonus',
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
                                $data['asset'] === 'matching_deposit_bonus' ? LedgerReference::MATCHINGDEPOSITBONUS : LedgerReference::DEPOSIT,
                                auth()->id(),
                                "made by admin | " . $data['description'],
                                $data['asset'] === 'matching_deposit_bonus' ? LedgerAsset::DEPOSITBONUSBALANCE : LedgerAsset::from($data['asset'])
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
                                    'matching_deposit_bonus' => 'Matching Deposit Bonus',
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
                            try {
                                WalletService::debit(
                                    $record,
                                    $data['amount'],
                                    LedgerReference::WITHDRAWAL,
                                    auth()->id(),
                                    "made by admin | " . $data['description'],
                                    $data['asset'] === 'matching_deposit_bonus' ? LedgerAsset::DEPOSITBONUSBALANCE : LedgerAsset::from($data['asset'])
                                );

                                Notification::make()
                                    ->title('Balance Updated')
                                    ->success()
                                    ->send();
                            } catch(Halt $e) {
                                Notification::make()
                                    ->title($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                    // ->visible(fn () => auth()->user()->hasRole(['super-admin'])),

                    // Assign an existing user as a downline of this user
                    Action::make('assignDownline')
                        ->label('Assign Downline')
                        ->icon('heroicon-o-user-plus')
                        ->color('info')
                        ->modalHeading(fn ($record) => "Assign a downline to {$record->name}")
                        ->modalDescription('Pick an existing user to place directly under this user in the referral tree — no database editing required.')
                        ->modalSubmitActionLabel('Assign')
                        ->form([
                            Select::make('downline_user_id')
                                ->label('User to add as downline')
                                ->helperText('Search by name or email.')
                                ->searchable()
                                ->live()
                                ->getSearchResultsUsing(function (string $search, $record) {
                                    return User::query()
                                        ->where('id', '!=', $record->id)
                                        ->where(function (Builder $q) use ($search) {
                                            $q->where('name', 'like', "%{$search}%")
                                                ->orWhere('email', 'like', "%{$search}%");
                                        })
                                        ->limit(20)
                                        ->get()
                                        ->mapWithKeys(fn ($u) => [$u->id => "{$u->name} ({$u->email})"])
                                        ->toArray();
                                })
                                ->getOptionLabelUsing(function ($value) {
                                    $u = User::find($value);

                                    return $u ? "{$u->name} ({$u->email})" : null;
                                })
                                ->required(),

                            Placeholder::make('impact_notice')
                                ->label('Before you proceed')
                                ->content(function (Get $get, $record) {
                                    $target = User::find($get('downline_user_id'));

                                    if (! $target) {
                                        return '';
                                    }

                                    if (ReferralReassignmentService::wouldCreateCycle($target, $record)) {
                                        return "⚠ {$record->name} is already downstream of {$target->name} — this assignment would create a circular referral chain and cannot proceed.";
                                    }

                                    $info = ReferralReassignmentService::inspect($target);
                                    $lines = [];

                                    if ($info['downline_count'] > 0) {
                                        $lines[] = "{$target->name} already has {$info['downline_count']} user(s) in their existing downline. All of that subtree's ancestor data will be recalculated to reflect the new upline.";
                                    }

                                    if ($info['has_upline']) {
                                        $lines[] = "Destructive: {$target->name} already has a sponsor" . ($info['current_upline'] ? " ({$info['current_upline']->name})" : '') . '. Proceeding will permanently replace it. Referral bonuses already paid out are not affected.';
                                    }

                                    if (empty($lines)) {
                                        $lines[] = "{$target->name} has no existing upline or downline — this is a simple assignment.";
                                    }

                                    return implode(' ', $lines);
                                })
                                ->visible(fn (Get $get) => filled($get('downline_user_id'))),
                        ])
                        ->requiresConfirmation()
                        ->action(function (array $data, $record) {
                            $target = User::find($data['downline_user_id']);

                            if (! $target) {
                                Notification::make()
                                    ->title('User not found')
                                    ->danger()
                                    ->send();

                                return;
                            }

                            try {
                                ReferralReassignmentService::reassign($target, $record);

                                Notification::make()
                                    ->title('Downline assigned')
                                    ->body("{$target->name} is now a downline of {$record->name}.")
                                    ->success()
                                    ->send();
                            } catch (InvalidArgumentException $e) {
                                Notification::make()
                                    ->title('Could not assign')
                                    ->body($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                    // end assign downline


                    // Make Leader
                    Action::make('makeLeader')
                        ->label('Make Leader')
                        ->icon('heroicon-o-shield-check')
                        ->color('success')
                        ->requiresConfirmation()
                        ->visible(fn ($record) => !$record->hasRole('leader'))
                        ->form([
                            CheckboxList::make('bonus_permissions')
                                ->label('Bonus Permissions')
                                ->options([
                                    'receive_referral' => 'Earn from Referrals',
                                    'distribute_referral' => 'Uplines Earn Referral Rewards',
                                    'receive_rank' => 'Can Receive Rank Rewards',
                                    'receive_residual_bonus' => 'Earn Daily Rank Passive Income',
                                    'none' => 'No Bonus Access',
                                ])
                                ->descriptions([
                                    'receive_referral' => 'User earns money when people they invited join or invest.',

                                    'distribute_referral' => 'User is allowed to allocate referral bonuses to uplines.',

                                    'receive_rank' => 'User earns rewards when they reach certain levels or milestones in the system.',

                                    'receive_residual_bonus' => 'User earns small daily income based on activity in their network or system performance.',

                                    'none' => 'User cannot receive or manage any bonuses.',
                                ])
                                ->columns(1)
                                ->required()
                                ->live()
                                ->afterStateUpdated(function ($state, callable $set) {
                                    if (in_array('none', $state)) {
                                        $set('bonus_permissions', ['none']);
                                        return;
                                    }

                                    $filtered = array_values(array_filter($state, fn ($item) => $item !== 'none'));

                                    $set('bonus_permissions', $filtered);
                                })
                        ])
                        ->action(function ($record, array $data) {

                            abort_unless(!$record->hasRole('leader'), 403);

                            $permissions = $data['bonus_permissions'] ?? [];

                            $record->assignRole('leader');

                            $record->can_receive_referral_bonus = in_array('receive_referral', $permissions);
                            $record->can_distribute_referral_bonus = in_array('distribute_referral', $permissions);
                            $record->can_receive_rank_bonus = in_array('receive_rank', $permissions);
                            $record->can_receive_rank_residual_bonus = in_array('receive_residual_bonus', $permissions);
                            $record->save();

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

                            $record->botLicenses()
                                ->where('expires_at', '>', now())
                                ->update([
                                    'expires_at' => now(),
                                    'status' => BotLicenseStatus::EXPIRED,
                                ]);

                            Notification::make()
                                ->title('User Suspended')
                                ->body('User suspended and all licenses expired.')
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
                            ->modalDescription('This will remove all admin privileges from this user.'),
                            // ->visible(fn ($record) => auth()->user()->hasRole('admin'))

                            // Unsuspending user action
                        Action::make('approveKyc')
                            ->label('Approve KYC')
                            ->icon('heroicon-o-check-circle')
                            ->color('success')
                            ->requiresConfirmation()
                            ->visible(fn ($record) => $record->kyc_status === 'pending' || $record->kyc_status === 'unsubmitted')
                            ->action(function ($record) {

                                abort_unless($record->kyc_status !== 'approved', 403);

                                $record->update([
                                    'kyc_status' => 'approved',
                                ]);

                                Notification::make()
                                    ->title('KYC Approved')
                                    ->body('This user now has full access to the full platform access.')
                                    ->success()
                                    ->send();
                            })
                            ->modalHeading('Approve KYC')
                            ->modalDescription('Are you sure you want to user\'s KYC?'),
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
