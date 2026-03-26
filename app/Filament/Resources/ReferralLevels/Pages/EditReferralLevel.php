<?php

namespace App\Filament\Resources\ReferralLevels\Pages;

use App\Filament\Resources\ReferralLevels\ReferralLevelResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReferralLevel extends EditRecord
{
    protected static string $resource = ReferralLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
