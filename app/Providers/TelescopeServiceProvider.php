<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    public function register(): void
    {
        $this->hideSensitiveRequestDetails();

        // record everything in local; in production keep the full picture but
        // let TELESCOPE_SAMPLE trim ordinary successful requests if volume grows
        Telescope::filter(function (IncomingEntry $entry) {
            if ($this->app->environment('local')) {
                return true;
            }

            // always keep the things you actually debug with
            if ($entry->isReportableException()
                || $entry->isFailedRequest()
                || $entry->isFailedJob()
                || $entry->isScheduledTask()
                || $entry->hasMonitoredTag()) {
                return true;
            }

            $sample = (int) env('TELESCOPE_SAMPLE', 100); // percent of the rest to keep

            return $sample >= 100 ? true : random_int(1, 100) <= $sample;
        });
    }

    protected function hideSensitiveRequestDetails(): void
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token', 'password', 'password_confirmation']);
        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
            'authorization',
        ]);
    }

    protected function gate(): void
    {
        // only signed-in admins can open /telescope
        Gate::define('viewTelescope', function ($user) {
            return $user->hasRole('admin');
        });
    }
}
