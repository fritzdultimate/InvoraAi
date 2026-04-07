<?php

namespace App\Filament\Resources\ReferralBonuses\Pages;

use App\Filament\Resources\ReferralBonuses\ReferralBonusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditReferralBonus extends EditRecord
{
    protected static string $resource = ReferralBonusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
