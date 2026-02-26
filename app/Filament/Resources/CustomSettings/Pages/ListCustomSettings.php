<?php

namespace App\Filament\Resources\CustomSettings\Pages;

use App\Filament\Resources\CustomSettings\CustomSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListCustomSettings extends ListRecords
{
    protected static string $resource = CustomSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
