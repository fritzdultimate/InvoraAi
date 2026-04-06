<?php

namespace App\Filament\Resources\BotTerminations\Pages;

use App\Filament\Resources\BotTerminations\BotTerminationResource;
use Filament\Actions\DeleteAction;
use Filament\Actions\ForceDeleteAction;
use Filament\Actions\RestoreAction;
use Filament\Resources\Pages\EditRecord;

class EditBotTermination extends EditRecord
{
    protected static string $resource = BotTerminationResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
            ForceDeleteAction::make(),
            RestoreAction::make(),
        ];
    }
}
