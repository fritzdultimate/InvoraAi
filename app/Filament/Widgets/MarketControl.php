<?php

namespace App\Filament\Widgets;

// use Filament\Widgets\StatsOverviewWidget;
// use Filament\Widgets\StatsOverviewWidget\Stat;

// class MarketControl extends StatsOverviewWidget
// {
//     protected function getStats(): array
//     {
//         return [
//             //
//         ];
//     }
// }

use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Widgets\StatsOverviewWidget as BaseWidget;
use Filament\Widgets\StatsOverviewWidget\Stat;
use Illuminate\Support\Facades\Cache;

class MarketControl extends BaseWidget
{
    protected function getStats(): array
    {
        $trend = Cache::get('market_trend', 'neutral');
        $expiresAt = Cache::get('market_trend_expires_at');

        return [

            Stat::make('Market State', ucfirst($trend))
                ->description(
                    $expiresAt
                        ? 'Expires ' . $expiresAt->diffForHumans()
                        : 'No expiry'
                )
                ->color(match ($trend) {
                    'bull' => 'success',
                    'bear' => 'danger',
                    default => 'gray',
                })
                ->icon(match ($trend) {
                    'bull' => 'heroicon-o-arrow-trending-up',
                    'bear' => 'heroicon-o-arrow-trending-down',
                    default => 'heroicon-o-minus',
                }),


        ];
    }

    protected function getHeaderActions(): array {
        return [
            Action::make('setMarket')
                ->label('Set Market')
                ->icon('heroicon-o-adjustments-horizontal')

                ->form([
                    Select::make('trend')
                        ->options([
                            'bull' => 'Bullish 🚀',
                            'neutral' => 'Neutral 😐',
                            'bear' => 'Bearish 📉',
                        ])
                        ->required(),

                    TextInput::make('duration')
                        ->numeric()
                        ->label('Duration (hours)')
                        ->required()
                        ->minValue(1),
                ])

                ->action(function (array $data) {

                    $expiresAt = now()->addHours($data['duration']);

                    Cache::put('market_trend', $data['trend'], $expiresAt);
                    Cache::put('market_trend_expires_at', $expiresAt, $expiresAt);

                    // optional feedback
                    $this->dispatch('notify', message: 'Market updated');
                }),
        ];
    }
}