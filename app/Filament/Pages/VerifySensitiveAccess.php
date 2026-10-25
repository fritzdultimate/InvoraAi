<?php

namespace App\Filament\Pages;

use App\Services\Admin\AdminLogger;
use App\Services\Admin\SensitiveAccess;
use Filament\Actions\Action;
use Filament\Auth\MultiFactor\App\AppAuthentication;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\RateLimiter;

class VerifySensitiveAccess extends Page
{
    protected static bool $shouldRegisterNavigation = false;
    protected static ?string $title = 'Security check';
    protected static ?string $slug = 'security-check';

    public ?array $data = [];

    public function mount(): void
    {
        if (SensitiveAccess::isVerified()) {
            $this->redirect(session()->pull('sensitive_intended', Filament::getUrl()));

            return;
        }

        SensitiveAccess::sendEmailCode(auth()->user());

        $this->form->fill();
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Confirm it is you')
                    ->description('Payment gateway and custom settings need two codes: one from your authenticator app and one we just emailed to ' . auth()->user()->email . '. This lasts ' . SensitiveAccess::MINUTES . ' minutes.')
                    ->icon('heroicon-o-shield-check')
                    ->schema([
                        TextInput::make('app_code')
                            ->label('Authenticator app code')
                            ->required()
                            ->length(6)
                            ->autocomplete('one-time-code'),
                        TextInput::make('email_code')
                            ->label('Email code')
                            ->required()
                            ->length(6),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('verify')
                ->footer([
                    Actions::make([
                        Action::make('verify')->label('Verify')->submit('verify'),
                        Action::make('resend')
                            ->label('Resend email code')
                            ->color('gray')
                            ->action('resendCode'),
                    ]),
                ]),
        ]);
    }

    public function resendCode(): void
    {
        if (SensitiveAccess::sendEmailCode(auth()->user())) {
            Notification::make()->title('A new code was sent to your email')->success()->send();
        } else {
            Notification::make()->title('Wait a minute before asking for another code')->warning()->send();
        }
    }

    public function verify(): void
    {
        $user = auth()->user();
        $key = "sensitive-verify:{$user->id}";

        if (RateLimiter::tooManyAttempts($key, 5)) {
            Notification::make()
                ->title('Too many attempts. Try again in ' . RateLimiter::availableIn($key) . ' seconds.')
                ->danger()
                ->send();

            return;
        }

        $data = $this->form->getState();

        $appOk = filled($user->admin_2fa_secret)
            && AppAuthentication::make()->verifyCode($data['app_code'], $user->admin_2fa_secret);
        $emailOk = $appOk && SensitiveAccess::checkEmailCode($user, $data['email_code']);

        if (! $appOk || ! $emailOk) {
            RateLimiter::hit($key, 300);
            AdminLogger::record('sensitive_failed', $user, 'Failed security check for gateway / custom settings');

            Notification::make()->title('One of the codes is wrong')->danger()->send();

            return;
        }

        RateLimiter::clear($key);
        SensitiveAccess::markVerified();
        AdminLogger::record('sensitive_verified', $user, 'Passed security check for gateway / custom settings');

        $this->redirect(session()->pull('sensitive_intended', Filament::getUrl()));
    }
}
