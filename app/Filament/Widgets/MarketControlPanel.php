<?php

namespace App\Filament\Widgets;

use Filament\Widgets\Widget;
use Illuminate\Support\Facades\Cache;

class MarketControlPanel extends Widget
{
    protected string $view = 'filament.widgets.market-control-panel';

    public function setBull()
    {
        $this->setMarket('bull');
    }

    public function setNeutral()
    {
        $this->setMarket('neutral');
    }

    public function setBear()
    {
        $this->setMarket('bear');
    }

    protected function setMarket(string $trend)
    {
        $expiresAt = now()->addHours(6);

        Cache::put('market_trend', $trend, $expiresAt);
        Cache::put('market_trend_expires_at', $expiresAt, $expiresAt);

        $this->dispatch('$refresh');
    }
}
