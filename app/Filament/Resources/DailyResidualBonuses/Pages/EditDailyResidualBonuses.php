<?php

namespace App\Filament\Resources\DailyResidualBonuses\Pages;

use App\Filament\Resources\DailyResidualBonuses\DailyResidualBonusesResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditDailyResidualBonuses extends EditRecord
{
    protected static string $resource = DailyResidualBonusesResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
