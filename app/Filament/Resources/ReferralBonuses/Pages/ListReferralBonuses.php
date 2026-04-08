<?php

namespace App\Filament\Resources\ReferralBonuses\Pages;

use App\Filament\Resources\ReferralBonuses\ReferralBonusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReferralBonuses extends ListRecords
{
    protected static string $resource = ReferralBonusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
