<?php

namespace App\Filament\Resources\Deposits\Schemas;

use App\Models\User;
use App\Services\Admin\AdminDepositService;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Illuminate\Database\Eloquent\Builder;

class AdminDepositForm
{
    public static function fields(): array
    {
        return [
            Select::make('user_id')
                ->label('User')
                ->searchable()
                ->required()
                ->getSearchResultsUsing(fn (string $search) => User::query()
                    ->where(fn (Builder $q) => $q->where('name', 'like', "%{$search}%")->orWhere('email', 'like', "%{$search}%"))
                    ->limit(20)
                    ->get()
                    ->mapWithKeys(fn ($u) => [$u->id => "{$u->name} ({$u->email})"])
                    ->toArray())
                ->getOptionLabelUsing(fn ($value) => ($u = User::find($value)) ? "{$u->name} ({$u->email})" : null),

            Select::make('status')
                ->options(AdminDepositService::STATUSES)
                ->default('finished')
                ->required()
                ->live(),

            TextInput::make('amount')
                ->label('Amount requested')
                ->numeric()
                ->prefix('$')
                ->minValue(0.01)
                ->required()
                ->live(onBlur: true)
                ->afterStateUpdated(function (Get $get, Set $set, $state) {
                    if ($get('status') === 'finished') {
                        $set('actually_paid', $state);
                    }
                }),

            TextInput::make('actually_paid')
                ->label('Actually paid')
                ->helperText('What counts for team volume and referral bonus.')
                ->numeric()
                ->prefix('$')
                ->minValue(0.01)
                ->required(fn (Get $get) => $get('status') !== 'pending')
                ->visible(fn (Get $get) => $get('status') !== 'pending'),

            TextInput::make('currency')
                ->default('usdt')
                ->required()
                ->maxLength(20),

            DateTimePicker::make('date')
                ->label('Deposit date')
                ->helperText('Team volume is counted by this date.')
                ->default(now())
                ->maxDate(now()->addMinute())
                ->required(),

            Toggle::make('credit_wallet')
                ->label("Credit the user's deposit balance")
                ->helperText('Turn this off if you already funded this user and only want the deposit on record.')
                ->default(true)
                ->visible(fn (Get $get) => $get('status') !== 'pending'),

            Toggle::make('distribute_bonus')
                ->label('Distribute referral bonus to upline now')
                ->helperText('You can also do this later from the deposit.')
                ->default(true)
                ->visible(fn (Get $get) => $get('status') !== 'pending'),

            Textarea::make('note')
                ->label('Reason / note')
                ->required()
                ->maxLength(250),
        ];
    }
}
