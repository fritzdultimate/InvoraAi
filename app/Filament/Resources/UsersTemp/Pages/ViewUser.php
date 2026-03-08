<?php

namespace App\Filament\Resources\Users\Pages;

use App\Filament\Widgets\UserOverview;
use Filament\Resources\Pages\ViewRecord;
use App\Filament\Resources\Users\UserResource;

class ViewUser extends ViewRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderWidgets(): array
    {
        return [
            UserOverview::class,
        ];
    }

    protected function getHeaderActions(): array
    {
        return [];
    }
}
