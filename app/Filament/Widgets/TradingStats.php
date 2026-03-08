<?php

namespace App\Filament\Widgets;

use App\Models\BotLicense;
use App\Models\BotProfitCycle;
use App\Models\User;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class TradingStats extends StatsOverviewWidget {
    protected function getStats(): array
    {
        return [
            Stat::make('Total Trading Capital', '$' . number_format(User::sum(column: 'locked_balance'), 2))
                ->description('Capital in trades')
                ->color('primary'),

            Stat::make('Active AI Bots', BotLicense::where('expires_at', '>', now())->count())
                ->description('Bot license at work')
                ->color('info'),

            Stat::make('Commisions paid', '$' . number_format(BotProfitCycle::sum(column: 'profit_amount'), 2))
                ->description('Commissions paid to investors')
                ->color('success'),
        ];
    }
}
