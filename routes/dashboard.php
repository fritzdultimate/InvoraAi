<?php

use App\Livewire\Dashboard\Bot;
use App\Livewire\Dashboard\Deposit;
use App\Livewire\Dashboard\DepositDetails;
use App\Livewire\Dashboard\DepositPage;
use App\Livewire\Dashboard\Investment;
use App\Livewire\Dashboard\InvestmentItem;
use App\Livewire\Dashboard\Overview;
use App\Livewire\Dashboard\Profile;
use App\Livewire\Dashboard\Referral\DirectReferrals;
use App\Livewire\Dashboard\Referral\Ranking;
use App\Livewire\Dashboard\Settings;
use App\Livewire\Dashboard\Support\CreateTicket;
use App\Livewire\Dashboard\Support\Tickets;
use App\Livewire\Dashboard\Support\ViewTicket;
use App\Livewire\Dashboard\Withdrawal;
use App\Livewire\Dashboard\WithdrawalDetails;
use App\Livewire\Dashboard\WithdrawalPage;
use App\Models\Notification;
use Illuminate\Support\Facades\Route;

Route::middleware(['auth'])->prefix('dashboard')->group(function () {
    Route::get('/', Overview::class)->name('dashboard');
    Route::get('/overview', Overview::class)->name('dashboard');

    Route::get('/deposit', Deposit::class)->name('deposit');

    Route::get('/deposit/{deposit}', DepositPage::class)->name('deposit.page');

    Route::get('/withdrawal', Withdrawal::class)->name('withdrawal');
    Route::get('/withdrawal/details', WithdrawalDetails::class)->name('withdrawal.details');
    Route::get('/withdrawal/{withdrawal}', WithdrawalPage::class)->name('withdrawal.page');

    Route::get('/bot', Bot::class)->name('bot');

    Route::get('/investment',  Investment::class)->name('investments.create');

    Route::get('/investment/{id}',  InvestmentItem::class)->name('investments.item');


    Route::get('/profile',  Profile::class)->name('profile');
    Route::get('/settings',  Settings::class)->name('settings');


    
    Route::get('/referrals', \App\Livewire\Dashboard\Referral\Overview::class)->name('dashboard.referrals');
    Route::get('/bonuses', \App\Livewire\Dashboard\Referral\Bonuses::class)->name('dashboard.bonuses');
    Route::get('/referrals/direct', DirectReferrals::class)->name('dashboard.referrals.direct');
    Route::get('/referrals/network', \App\Livewire\Dashboard\Referral\MyNetwork::class)->name('dashboard.referrals.network');
    Route::get('/referrals/tree', \App\Livewire\Dashboard\Referral\TreeView::class)->name('dashboard.referrals.tree');

    Route::get('/ranking', Ranking::class)->name('ranking');


    Route::prefix('support')->group(function () {
        Route::get('/', Tickets::class)->name('support.index');
        Route::get('/create', CreateTicket::class)->name('support.create');
        Route::get('/{ticket}', ViewTicket::class)->name('support.view');
    });


});

Route::post('/notifications/read/{id}', function ($id) {
    $notification = Notification::findOrFail($id);

    auth()->user()->notifications()->syncWithoutDetaching([
        $notification->id => [
            'read_at' => now()
        ]
    ]);

    return response()->json($notification);
});
