<?php

namespace App\Filament\Resources\DailyResidualBonuses\Pages;

use App\Filament\Resources\DailyResidualBonuses\DailyResidualBonusesResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDailyResidualBonuses extends ListRecords
{
    protected static string $resource = DailyResidualBonusesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
