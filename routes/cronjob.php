<?php

use App\Http\Controllers\DepositController;
use App\Http\Controllers\ProfitController;
use App\Http\Controllers\RankController;
use App\Http\Controllers\TradeController;
use App\Http\Controllers\TradeMultiexchangeController;
use Illuminate\Support\Facades\Route;

Route::get('cron/investment/profit/distribute', [ProfitController::class, 'distribute']);

Route::get('/cron/assign-rank', [RankController::class, 'assignRank'])->name('rank.assign');

Route::get('/cron/deposit/mark/expire', [DepositController::class, 'markAsExpired'])->name('deposit.expire');

Route::get('/cron/trading/execute-cycle', [TradeMultiexchangeController::class, 'executeTradingCycle'])
    ->name('trading.execute');

// Route::get('/trading/test-funding-window/{symbol}', [TradeMultiexchangeController::class, 'testFundingRates'])->name('trading.funding-window');

