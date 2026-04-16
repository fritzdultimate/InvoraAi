<?php

namespace App\Filament\Resources\RankBonuses\Pages;

use App\Filament\Resources\RankBonuses\RankBonusResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRankBonuses extends ListRecords
{
    protected static string $resource = RankBonusResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
