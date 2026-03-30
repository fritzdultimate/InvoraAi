<?php

namespace App\Filament\Widgets;

use App\Models\Rank;
use Filament\Widgets\StatsOverviewWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;

class AverageRankRequirements extends StatsOverviewWidget
{
    protected function getStats(): array
    {
        $rank = Rank::orderByDesc('required_volume')->first();
        $latestRank = Rank::latest('updated_at')->first();
        return [
            Stat::make('Total Ranks', Rank::count())
                ->description('Configured ranking levels')
                ->icon('heroicon-o-star')
                ->color('primary'),
            Stat::make(
                'Avg Volume',
                number_format(Rank::avg('required_volume'), 2)
            )
                ->description('Average required volume')
                ->icon('heroicon-o-chart-bar')
                ->color('info'),


                
            Stat::make(
                'Highest Rank',
                Rank::max('level') ?? 0
            )
            ->description('Top progression level')
            ->icon('heroicon-o-arrow-trending-up')
            ->color('success'),

            Stat::make(
                'Hardest Rank',
                $rank?->name ?? '—'
            )
            ->description('Highest volume requirement')
            ->icon('heroicon-o-fire')
            ->color('danger'),

            Stat::make(
                'Last Updated Rank',
                $latestRank?->name ?? '—'
            )
            ->description($latestRank?->updated_at?->diffForHumans())
            ->icon('heroicon-o-clock')
            ->color('gray'),
        
        ];
    }
}
