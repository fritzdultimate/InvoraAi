<?php

use App\Jobs\RunBotProfitCycle;
use App\Models\Trade;
use App\Models\TradingAsset;
use App\Services\TradeSimulatorService;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function($schedule) {
    $schedule->job(new RunBotProfitCycle())->everySixHours();
});


Schedule::call(function($schedule) {
    $simulator = new TradeSimulatorService();

    // Open new trades
    TradingAsset::where('active', true)->each(function ($asset) use ($simulator) {
        // if (rand(0, 1)) {
            $simulator->openTrade($asset);
        // }
    });

    // Update open trades
    // Trade::where('status', 'open')->each(function ($trade) use ($simulator) {
    //     $simulator->updateTrade($trade);
    // });
})->everyMinute();
