<?php

namespace App\Filament\Resources\PaymentSettings\Pages;

use App\Filament\Concerns\RequiresSensitiveAccess;
use App\Filament\Resources\PaymentSettings\PaymentSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPaymentSettings extends ListRecords
{
    use RequiresSensitiveAccess;

    protected static string $resource = PaymentSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
