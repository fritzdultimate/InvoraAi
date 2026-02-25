<?php

namespace App\Filament\Widgets;

use Filament\Notifications\Notification;
use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;

class MarketControlPanel extends Widget
{
    protected string $view = 'filament.widgets.market-control-panel';

    public function setBull()
    {
        $this->setMarket('bull');
        Notification::make()
            ->title('Market Simulation')
            ->body('Market state set to bull, profits will simulate high market')
            ->success()
            ->send();
    }

    public function setNeutral()
    {
        $this->setMarket('neutral');
        Notification::make()
            ->title('Market Simulation')
            ->body('Market state set to neutral, profits will simulate normal market')
            ->success()
            ->send();
    }

    public function setBear()
    {
        $this->setMarket('bear');
        Notification::make()
            ->title('Market Simulation')
            ->body('Market state set to bear, profits will simulate low market')
            ->success()
            ->send();
    }

    protected function setMarket(string $trend)
    {
        $expiresAt = now()->addHours(6);

        Cache::put('market_trend', $trend, $expiresAt);
        Cache::put('market_trend_expires_at', $expiresAt, $expiresAt);

        $this->dispatch('$refresh');
    }
}
