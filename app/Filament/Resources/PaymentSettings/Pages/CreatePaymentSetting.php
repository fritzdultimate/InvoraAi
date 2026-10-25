<?php

namespace App\Filament\Resources\PaymentSettings\Pages;

use App\Filament\Concerns\RequiresSensitiveAccess;
use App\Filament\Resources\PaymentSettings\PaymentSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentSetting extends CreateRecord
{
    use RequiresSensitiveAccess;

    protected static string $resource = PaymentSettingResource::class;
}
