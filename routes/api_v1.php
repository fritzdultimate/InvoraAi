<?php

use App\Http\Controllers\Api\V1\AuthController;
use App\Http\Controllers\Api\V1\BotController;
use App\Http\Controllers\Api\V1\DashboardController;
use App\Http\Controllers\Api\V1\NotificationController;
use App\Http\Controllers\Api\V1\ProfileController;
use App\Http\Controllers\Api\V1\ReferralController;
use App\Http\Controllers\Api\V1\WalletController;
use Illuminate\Support\Facades\Route;

Route::prefix('auth')->middleware('throttle:10,1')->group(function () {
    Route::post('/login', [AuthController::class, 'login']);
    Route::post('/two-factor', [AuthController::class, 'twoFactor']);
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/forgot-password', [AuthController::class, 'forgotPassword']);
    Route::post('/resend-verification', [AuthController::class, 'resendVerification']);
});

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/me', [AuthController::class, 'me']);
    Route::post('/auth/logout', [AuthController::class, 'logout']);

    Route::get('/dashboard', [DashboardController::class, 'index']);
    Route::get('/transactions', [DashboardController::class, 'transactions']);

    Route::prefix('wallet')->group(function () {
        Route::get('/deposit-options', [WalletController::class, 'depositOptions']);
        Route::get('/deposits', [WalletController::class, 'deposits']);
        Route::post('/deposits', [WalletController::class, 'storeDeposit'])->middleware('throttle:10,1');
        Route::get('/deposits/{id}', [WalletController::class, 'showDeposit']);

        Route::get('/withdrawal-options', [WalletController::class, 'withdrawalOptions']);
        Route::get('/withdrawals', [WalletController::class, 'withdrawals']);
        Route::post('/withdrawals', [WalletController::class, 'storeWithdrawal'])->middleware('throttle:6,1');

        Route::post('/convert', [WalletController::class, 'convert'])->middleware('throttle:10,1');
    });

    Route::get('/bots', [BotController::class, 'index']);
    Route::post('/bots/{bot}/license', [BotController::class, 'buyLicense'])->middleware('throttle:10,1');

    Route::get('/investments', [BotController::class, 'investments']);
    Route::post('/investments', [BotController::class, 'storeInvestment'])->middleware('throttle:10,1');
    Route::get('/investments/{uuid}', [BotController::class, 'showInvestment']);

    Route::get('/referrals', [ReferralController::class, 'index']);
    Route::get('/referrals/direct', [ReferralController::class, 'direct']);
    Route::get('/referrals/bonuses', [ReferralController::class, 'bonuses']);

    Route::put('/profile', [ProfileController::class, 'update']);
    Route::put('/profile/password', [ProfileController::class, 'password'])->middleware('throttle:6,1');
    Route::put('/profile/preferences', [ProfileController::class, 'preferences']);

    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread', [NotificationController::class, 'unread']);
    Route::post('/notifications/read-all', [NotificationController::class, 'readAll']);
    Route::post('/notifications/{id}/read', [NotificationController::class, 'read']);
});
