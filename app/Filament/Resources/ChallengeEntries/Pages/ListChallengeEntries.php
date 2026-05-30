<?php

namespace App\Filament\Resources\ChallengeEntries\Pages;

use App\Filament\Resources\ChallengeEntries\ChallengeEntryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChallengeEntries extends ListRecords
{
    protected static string $resource = ChallengeEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
