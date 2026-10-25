<?php

namespace App\Filament\Concerns;

use App\Filament\Pages\VerifySensitiveAccess;
use App\Services\Admin\SensitiveAccess;

// add to a Filament page to put it behind the authenticator + email code check
trait RequiresSensitiveAccess
{
    public function mountRequiresSensitiveAccess(): void
    {
        if (! SensitiveAccess::isVerified()) {
            session(['sensitive_intended' => request()->fullUrl()]);

            $this->redirect(VerifySensitiveAccess::getUrl());
        }
    }

    // every save, delete or toggle is a new request, so check again
    public function hydrateRequiresSensitiveAccess(): void
    {
        if (! SensitiveAccess::isVerified()) {
            abort(403, 'Your security check expired. Reload the page and verify again.');
        }
    }
}
