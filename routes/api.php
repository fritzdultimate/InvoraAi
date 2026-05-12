<?php

use App\Http\Controllers\NowPaymentsController;
use App\Http\Controllers\TradeController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/nowpayments', [NowPaymentsController::class, 'webhook'])->name('webhooks.nowpayments');

Route::get('/trading/status', [TradeController::class, 'getTradingStatus'])
    ->name('trading.status');
 
Route::get('/trading/funding-window', [TradeController::class, 'checkFundingWindow'])
    ->name('trading.funding-window');