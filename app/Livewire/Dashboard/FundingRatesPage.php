<?php

namespace App\Livewire\Dashboard;

use App\Models\FundingRate;
use App\Services\FundingRateService;
use Illuminate\Support\Facades\Cache;
use Livewire\Component;

class FundingRatesPage extends Component {
    public function poll() {
        Cache::lock('funding-rate-sync-lock', 5)->get(function () {
            if (Cache::has('funding-rate-last-synced')) {
                return;
            }

            FundingRateService::syncAll();

            Cache::put('funding-rate-last-synced', now(), now()->addSeconds(3));
        });
    }

    public function render() {
        $stablecoin = FundingRate::where('margin_type', 'stablecoin')
            ->orderByDesc('updated_at')
            ->get()
            ->groupBy('coin');

        $coinMargined = FundingRate::where('margin_type', 'coin')
            ->orderByDesc('updated_at')
            ->get()
            ->groupBy('coin');

        return view('livewire.dashboard.funding-rates-page', [
            'stablecoin' => $stablecoin,
            'coinMargined' => $coinMargined,
        ]);
    }
}