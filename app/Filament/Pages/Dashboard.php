<?php

namespace App\Filament\Pages;

use App\Filament\Widgets\MarketControl;
use App\Filament\Widgets\TotalDeposits;
use App\Filament\Widgets\TotalWithdrawals;
use App\Filament\Widgets\TradingStats;
use App\Filament\Widgets\UserStats;
use Filament\Pages\Page;

class Dashboard extends Page
{
    protected string $view = 'filament.pages.dashboard';

    protected function getHeaderWidgets(): array
    {
        return [
            TotalDeposits::class,
            UserStats::class,
            TotalWithdrawals::class,
            TradingStats::class,
            MarketControl::class
        ];
    }   
}
