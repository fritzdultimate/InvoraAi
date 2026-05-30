<?php

namespace App\Filament\Resources\ChallengeEntries\Pages;

use App\Filament\Resources\ChallengeEntries\ChallengeEntryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChallengeEntry extends EditRecord
{
    protected static string $resource = ChallengeEntryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
