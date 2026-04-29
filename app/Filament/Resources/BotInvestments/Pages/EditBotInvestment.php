<?php

namespace App\Filament\Resources\BotInvestments\Pages;

use App\Filament\Resources\BotInvestments\BotInvestmentResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditBotInvestment extends EditRecord
{
    protected static string $resource = BotInvestmentResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
