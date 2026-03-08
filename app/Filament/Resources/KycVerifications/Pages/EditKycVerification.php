<?php

namespace App\Filament\Resources\KycVerifications\Pages;

use App\Filament\Resources\KycVerifications\KycVerificationResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditKycVerification extends EditRecord
{
    protected static string $resource = KycVerificationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
