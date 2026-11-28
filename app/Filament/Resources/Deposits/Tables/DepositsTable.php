<?php

namespace App\Filament\Resources\Deposits\Tables;

use App\Enums\DepositStatus;
use App\Models\Deposit;
use App\Services\Admin\AdminDepositService;
use App\Services\DepositService;
use Filament\Actions\Action;
use Filament\Actions\ActionGroup;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Support\Exceptions\Halt;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Illuminate\Support\HtmlString;

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
                    ->searchable(['email', 'name'])
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
                TextColumn::make('actually_paid')
                    ->money('usd')
                    ->sortable(),
                TextColumn::make('source')
                    ->badge()
                    ->formatStateUsing(fn (?string $state) => $state === 'admin' ? 'Admin' : 'Gateway')
                    ->color(fn (?string $state) => $state === 'admin' ? 'warning' : 'gray')
                    ->description(fn (Deposit $record) => $record->isAdminCreated() ? $record->creator?->name : null)
                    ->toggleable(),
            ])
            ->defaultSort('created_at', 'desc')

            ->filters([
                SelectFilter::make('source')
                    ->options(['gateway' => 'Gateway', 'admin' => 'Admin']),
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
                        ->modalHeading('Approve Deposit')
                        ->modalDescription('Confirm how much to credit the user. If they paid less than requested, enter the amount actually received — it can never exceed what was requested.')
                        ->modalSubmitActionLabel('Approve & Credit')
                        ->schema([
                            TextInput::make('amount')
                                ->label('Amount to Credit')
                                ->numeric()
                                ->required()
                                ->prefix('$')
                                ->step(0.01)
                                ->default(fn (Deposit $record) => (float) $record->amount)
                                ->minValue(0.01)
                                ->maxValue(fn (Deposit $record) => (float) $record->amount)
                                ->helperText(fn (Deposit $record) =>
                                    'Requested: $' . number_format((float) $record->amount, 2) . '. Lower this if the user underpaid — the deposit will be marked "partially paid" and the user credited exactly this amount.'
                                ),
                        ])
                        ->visible(fn (Deposit $record) =>
                            $record->status !== DepositStatus::FINISHED && $record->status !== DepositStatus::CANCELLED && $record->status !== DepositStatus::FAILED && $record->status !== DepositStatus::EXPIRED
                        )
                        ->action(function (array $data, Deposit $record) {
                            try {
                                DepositService::markAsFinished($record, (float) $data['amount']);

                                Notification::make()
                                    ->title('Deposit approved successfully')
                                    ->success()
                                    ->send();
                            } catch (Halt $e) {
                                Notification::make()
                                    ->title($e->getMessage())
                                    ->danger()
                                    ->send();
                            }
                        }),
                    Action::make('distributeReferral')
                        ->label('Distribute referral bonus')
                        ->icon('heroicon-o-share')
                        ->color('info')
                        ->requiresConfirmation()
                        ->modalHeading('Distribute referral bonus')
                        ->modalDescription(fn (Deposit $record) => 'Pay the upline of ' . $record->user?->email . ' on $' . number_format((float) $record->actually_paid, 2) . ' using the active referral levels. Each level is paid only once per deposit.')
                        ->modalSubmitActionLabel('Distribute')
                        ->visible(fn (Deposit $record) => AdminDepositService::canDistribute($record))
                        ->action(function (Deposit $record) {
                            try {
                                self::distributedNotice(AdminDepositService::distributeReferralBonus($record));
                            } catch (\DomainException $e) {
                                Notification::make()->title($e->getMessage())->danger()->send();
                            }
                        }),

                    Action::make('referralBonuses')
                        ->label('Referral bonuses')
                        ->icon('heroicon-o-users')
                        ->color('gray')
                        ->modalHeading(fn (Deposit $record) => 'Referral bonus for ' . $record->reference)
                        ->modalContent(fn (Deposit $record) => view('filament.deposits.referral-bonuses', ['deposit' => $record]))
                        ->modalSubmitAction(false)
                        ->modalCancelActionLabel('Close')
                        ->visible(fn (Deposit $record) => $record->referralBonuses()->exists()),

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

    // tells the admin who got paid and how much
    public static function distributedNotice(Collection $bonuses): void
    {
        if ($bonuses->isEmpty()) {
            Notification::make()
                ->title('No referral bonus was given')
                ->body('This user has no upline, no referral level is active, or every level was already paid.')
                ->warning()
                ->send();

            return;
        }

        $lines = $bonuses->map(fn ($b) => 'L' . $b->level . ' · ' . e($b->user?->name ?? 'Deleted user') . ' (' . e($b->user?->email) . ') — <b>$' . number_format((float) $b->amount, 2) . '</b>');

        Notification::make()
            ->title('Referral bonus distributed: $' . number_format((float) $bonuses->sum('amount'), 2))
            ->body(new HtmlString($lines->implode('<br>')))
            ->success()
            ->persistent()
            ->send();
    }
}
