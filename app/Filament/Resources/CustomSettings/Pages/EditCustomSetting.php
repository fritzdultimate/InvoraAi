<?php

namespace App\Filament\Resources\CustomSettings\Pages;

use App\Filament\Resources\CustomSettings\CustomSettingResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCustomSetting extends EditRecord
{
    protected static string $resource = CustomSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
