<?php

namespace App\Filament\Resources\CardProducts\Pages;

use App\Filament\Resources\CardProducts\CardProductResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCardProducts extends ListRecords
{
    protected static string $resource = CardProductResource::class;

    protected function getHeaderActions(): array
    {
        return [CreateAction::make()];
    }
}
