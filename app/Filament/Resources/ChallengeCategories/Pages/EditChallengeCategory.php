<?php

namespace App\Filament\Resources\ChallengeCategories\Pages;

use App\Filament\Resources\ChallengeCategories\ChallengeCategoryResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditChallengeCategory extends EditRecord
{
    protected static string $resource = ChallengeCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
