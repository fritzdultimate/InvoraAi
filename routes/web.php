<?php

use App\Livewire\Auth\ForgotPassword;
use App\Livewire\Auth\Login;
use App\Livewire\Auth\PasswordLinkSent;
use App\Livewire\Auth\PasswordResetSuccess;
use App\Livewire\Auth\Register;
use App\Livewire\Auth\RegistrationSuccess;
use App\Livewire\Auth\ResetPassword;
use App\Livewire\Auth\VerificationFailure;
use App\Livewire\Auth\VerificationSuccess;
use App\Livewire\Landing\AboutUs;
use App\Livewire\Landing\ContactUs;
use App\Livewire\Landing\Faq;
use App\Livewire\Landing\HowItWorks;
use App\Livewire\Landing\PortfolioManagement;
use App\Livewire\Landing\PrivacyPolicy;
use App\Livewire\Landing\RiskAssessment;
use App\Livewire\Landing\RiskDisclosure;
use App\Livewire\Landing\Terms;
use App\Livewire\Landing\TradingBots;
use App\Livewire\Landing\TradingExecution;
use App\Livewire\Landing\TradingGuideLines;
use App\Livewire\Settings\Appearance;
use App\Livewire\Landing\Index as LandingIndex;
use App\Livewire\Settings\Password;
use App\Livewire\Settings\Profile;
use App\Livewire\Settings\TwoFactor;
use Illuminate\Support\Facades\Route;
use Laravel\Fortify\Features;

require __DIR__.'/dashboard.php';

Route::get('/', LandingIndex::class)->name('home-landing');
Route::get('/about-us', AboutUs::class)->name('about-us');
Route::get('/contact-us', ContactUs::class)->name('contact-us');
Route::get('/frequently-asked-questions', Faq::class)->name('faq');
Route::get('/how-it-works', HowItWorks::class)->name('how-it-works');
Route::get('/privacy-policy', PrivacyPolicy::class)->name('privacy-policy');
Route::get('/terms-and-conditions', Terms::class)->name('terms');
Route::get('/risk-disclosure', RiskDisclosure::class)->name('risk-disclosure');
Route::get('/trading-bots', TradingBots::class)->name('trading-bots');
Route::get('/risk-assessment', RiskAssessment::class)->name('risk-assessment');
Route::get('/trading-execution', TradingExecution::class)->name('trading-execution');
Route::get('/portfolio-management', PortfolioManagement::class)->name('portfolio-management');
Route::get('/trading-guidelines', TradingGuideLines::class)->name('trading-guidelines');

// AUTH
Route::get('/login', Login::class)->name('login');
Route::get('/register', Register::class)->name('register');
Route::get('/registration-success', RegistrationSuccess::class)->name('registration-success');
Route::get('/verification-success', VerificationSuccess::class)->name('verification-success');
Route::get('/verification-failure', VerificationFailure::class)->name('verification-failure');
Route::get('/resend-verification', VerificationFailure::class)->name('resend.verification');

Route::get('/forgot-password', ForgotPassword::class)->name('password.request');
Route::get('/password/link-sent', PasswordLinkSent::class)->name('password.link.sent');
Route::get('/reset-password/{token}/{email}', ResetPassword::class)->name('password.reset');
Route::get('/password/reset-success', PasswordResetSuccess::class)->name('password.reset.success');


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
