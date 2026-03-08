<?php

namespace App\Filament\Widgets;

use App\Enums\DepositStatus;
use App\Models\Deposit;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TotalDeposits extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        return [
            Stat::make('Total Deposits', '$' . number_format(Deposit::sum('amount'), 2))
                ->description('All-time deposits')
                ->color('primary'),

            Stat::make('Total Finished Deposits', '$' . number_format(Deposit::where('status', DepositStatus::FINISHED)->sum('amount'), 2))
                ->description('Confirmed Deposits')
                ->color('primary'),

            Stat::make('Pending Deposits', Deposit::where('status', DepositStatus::PENDING)->count() + Deposit::where('status', DepositStatus::WAITING)->count())
                ->description('Deposits awaiting action')
                ->color('warning'),


            Stat::make('Finished Deposits', Deposit::where('status', DepositStatus::FINISHED)->count())
                ->color('success'),


            Stat::make('Cancelled Deposits', Deposit::where('status', DepositStatus::CANCELLED)->count())
                ->color('danger'),
        ];
    }
}
