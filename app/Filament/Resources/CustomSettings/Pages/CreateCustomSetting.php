<?php

namespace App\Filament\Resources\CustomSettings\Pages;

use App\Filament\Resources\CustomSettings\CustomSettingResource;
use Filament\Resources\Pages\CreateRecord;

class CreateCustomSetting extends CreateRecord
{
    protected static string $resource = CustomSettingResource::class;
}
