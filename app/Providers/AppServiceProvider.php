<?php

namespace App\Providers;

use App\Services\NotificationService;
use App\Services\Admin\AdminLogger;
use Filament\Facades\Filament;
use Illuminate\Auth\Events\Failed;
use Illuminate\Auth\Events\Login;
use Illuminate\Auth\Events\Logout;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void {
        View::composer('components.layouts.app', function ($view) {
            if (auth()->check()) {
                $notifications = NotificationService::getCriticalForUser(auth()->user());

                $notifications = NotificationService::getForUser(auth()->user());
            } else {
                $notifications = collect();
            }

            $view->with('layoutNotifications', $notifications);
        });

        $this->registerAdminLogging();
    }

    private function registerAdminLogging(): void {
        foreach (['created', 'updated', 'deleted'] as $event) {
            Event::listen("eloquent.{$event}: *", function (string $name, array $data) use ($event) {
                AdminLogger::modelEvent($event, $data[0]);
            });
        }

        Event::listen(Login::class, function (Login $e) {
            if (Filament::isServing()) {
                AdminLogger::record('login', $e->user, 'Signed in to admin', userId: $e->user->getAuthIdentifier());
            }
        });

        Event::listen(Logout::class, function (Logout $e) {
            if (Filament::isServing() && $e->user) {
                AdminLogger::record('logout', $e->user, 'Signed out of admin', userId: $e->user->getAuthIdentifier());
            }
        });

        Event::listen(Failed::class, function (Failed $e) {
            if (Filament::isServing()) {
                AdminLogger::record(
                    'login_failed',
                    $e->user,
                    'Failed admin sign-in for ' . ($e->credentials['email'] ?? 'unknown'),
                    userId: $e->user?->getAuthIdentifier(),
                );
            }
        });
    }
}
