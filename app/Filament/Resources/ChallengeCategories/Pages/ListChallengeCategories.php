<?php

namespace App\Filament\Resources\ChallengeCategories\Pages;

use App\Filament\Resources\ChallengeCategories\ChallengeCategoryResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListChallengeCategories extends ListRecords
{
    protected static string $resource = ChallengeCategoryResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
