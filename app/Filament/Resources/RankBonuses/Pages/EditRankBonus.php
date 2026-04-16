<?php

namespace App\Filament\Resources\RankBonuses\Pages;

use App\Filament\Resources\RankBonuses\RankBonusResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRankBonus extends EditRecord
{
    protected static string $resource = RankBonusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
