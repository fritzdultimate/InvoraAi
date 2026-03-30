<?php

namespace App\Filament\Resources\Ranks\Pages;

use App\Filament\Resources\Ranks\RankResource;
use App\Filament\Widgets\AverageRankRequirements;
use App\Filament\Widgets\HardestRank;
use App\Filament\Widgets\HighestRankLevel;
use App\Filament\Widgets\RecentlyUpdatedRank;
use App\Filament\Widgets\TotalRanks;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRanks extends ListRecords
{
    protected static string $resource = RankResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }

    protected function getHeaderWidgets(): array {
        return [
            AverageRankRequirements::class,

        ];
    }
}
