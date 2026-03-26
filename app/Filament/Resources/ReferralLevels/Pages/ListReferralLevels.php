<?php

namespace App\Filament\Resources\ReferralLevels\Pages;

use App\Filament\Resources\ReferralLevels\ReferralLevelResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListReferralLevels extends ListRecords
{
    protected static string $resource = ReferralLevelResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
