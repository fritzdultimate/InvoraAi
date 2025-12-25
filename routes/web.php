<?php

use App\Livewire\Auth\Login;
use App\Livewire\Landing\AboutUs;
use App\Livewire\Landing\ContactUs;
use App\Livewire\Landing\Faq;
use App\Livewire\Landing\HowItWorks;
use App\Livewire\Landing\PrivacyPolicy;
use App\Livewire\Landing\RiskDisclosure;
use App\Livewire\Landing\Terms;
use App\Livewire\Landing\TradingBots;
use App\Livewire\Settings\Appearance;
use App\Livewire\Landing\Index as LandingIndex;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

Route::get('/', LandingIndex::class)->name('home-landing');
Route::get('/about-us', AboutUs::class)->name('about-us');
Route::get('/contact-us', ContactUs::class)->name('contact-us');
Route::get('/frequently-asked-questions', Faq::class)->name('faq');
Route::get('/how-it-works', HowItWorks::class)->name('how-it-works');
Route::get('/privacy-policy', PrivacyPolicy::class)->name('privacy-policy');
Route::get('/terms-and-conditions', Terms::class)->name('terms');
Route::get('/risk-disclosure', RiskDisclosure::class)->name('risk-disclosure');
Route::get('/trading-bots', TradingBots::class)->name('trading-bots');

// AUTH
Route::get('/login', Login::class);


Route::middleware(['auth'])->group(function () {
    Route::redirect('settings', 'settings/profile');

    Route::get('settings/profile', Profile::class)->name('profile.edit');
    Route::get('settings/password', Password::class)->name('user-password.edit');
    Route::get('settings/appearance', Appearance::class)->name('appearance.edit');

    Route::get('settings/two-factor', TwoFactor::class)
        ->middleware(
            when(
                Features::canManageTwoFactorAuthentication()
                    && Features::optionEnabled(Features::twoFactorAuthentication(), 'confirmPassword'),
                ['password.confirm'],
                [],
            ),
        )
        ->name('two-factor.show');
});
