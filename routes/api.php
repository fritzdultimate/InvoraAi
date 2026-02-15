<?php

use App\Http\Controllers\NowPaymentsController;
use Illuminate\Support\Facades\Route;

Route::post('/webhooks/nowpayments', [NowPaymentsController::class, 'webhook'])->name('webhooks.nowpayments');