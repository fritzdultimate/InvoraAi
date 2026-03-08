<?php

namespace App\Filament\Widgets;

use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class UserStats extends StatsOverviewWidget {
    protected function getStats(): array
    {
        return [
            Stat::make('Total Users', User::count())
                ->description('Registered users')
                ->color('primary'),

            Stat::make('Total Verified', User::whereNotNull('email_verified_at')->count())
                ->description('Registered users')
                ->color('success'),

            Stat::make('Total Suspended', User::whereNotNull('suspended_at')->count())
                ->description('Suspended users')
                ->color('danger'),

            Stat::make('New Users Today', User::whereBetween('created_at', [now()->startOfDay(), now()->endOfDay()])->count())
                ->description('Users registered today')
                ->color('success'),
        ];
    }
}
