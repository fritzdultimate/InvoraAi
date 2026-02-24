<?php

use App\Livewire\Dashboard\Bot;
use App\Livewire\Dashboard\Deposit;
use App\Livewire\Dashboard\DepositPage;
use App\Livewire\Dashboard\Investment;
use App\Livewire\Dashboard\InvestmentItem;
use App\Livewire\Dashboard\Overview;
use App\Livewire\Dashboard\Profile;
use App\Livewire\Dashboard\Settings;
use App\Livewire\Dashboard\Withdrawal;
use App\Livewire\Dashboard\WithdrawalPage;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::get('/', Overview::class)->name('dashboard');
    Route::get('/overview', Overview::class)->name('dashboard');

    Route::get('/deposit', Deposit::class)->name('deposit');

    Route::get('/deposit/{deposit}', DepositPage::class)->name('deposit.page');

    Route::get('/withdrawal', Withdrawal::class)->name('withdrawal');
    Route::get('/withdrawal/{withdrawal}', WithdrawalPage::class)->name('withdrawal.page');

    Route::get('/bot', Bot::class)->name('bot');

    Route::get('/investment',  Investment::class)->name('investments.create');

    Route::get('/investment/{id}',  InvestmentItem::class)->name('investments.item');


    Route::get('/profile',  Profile::class)->name('profile');
    Route::get('/settings',  Settings::class)->name('settings');


});
