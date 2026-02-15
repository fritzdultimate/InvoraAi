<?php

use App\Jobs\RunBotProfitCycle;
use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Schedule::call(function($schedule) {
    $schedule->job(new RunBotProfitCycle())->everySixHours();
});

// $schedule->call(fn() => app(BotLicenseService::class)->expireLicenses())
//     ->hourly();