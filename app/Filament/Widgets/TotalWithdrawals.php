<?php

namespace App\Filament\Widgets;

use App\Enums\DepositStatus;
use App\Enums\WithdrawalStatus;
use App\Models\Deposit;
use App\Models\Withdrawal;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalWithdrawals extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Withdrawals', '$' . number_format(Withdrawal::sum('amount'), 2))
                ->description('All-time withdrawals')
                ->color('primary'),

            Stat::make('Total Completed Withdrawals', '$' . number_format(Withdrawal::where('status', WithdrawalStatus::COMPLETED)->sum('amount'), 2))
                ->description('Completed Withdrawals')
                ->color('success'),

            Stat::make('Pending Withdrawals', Deposit::where('status', WithdrawalStatus::PENDING)->count())
                ->description('Withdrawals awaiting action')
                ->color('warning'),


            Stat::make('Cancelled Withdrawals', Deposit::where('status', WithdrawalStatus::CANCELLED)->count())
                ->color('danger'),
        ];
    }
}
