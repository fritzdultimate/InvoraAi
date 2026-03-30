<?php

use App\Http\Controllers\ProfitController;
use App\Http\Controllers\RankController;
use Illuminate\Support\Facades\Route;

Route::get('cron/investment/profit/distribute', [ProfitController::class, 'distribute']);

Route::get('/cron/assign-rank', [RankController::class, 'assignRank'])
    ->name('rank.assign');