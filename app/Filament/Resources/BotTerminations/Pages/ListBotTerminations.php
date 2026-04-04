<?php

namespace App\Filament\Resources\BotTerminations\Pages;

use App\Filament\Resources\BotTerminations\BotTerminationResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListBotTerminations extends ListRecords
{
    protected static string $resource = BotTerminationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
