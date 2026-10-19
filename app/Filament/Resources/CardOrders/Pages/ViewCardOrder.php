<?php

namespace App\Filament\Resources\CardOrders\Pages;

use App\Filament\Resources\CardOrders\CardOrderActions;
use App\Filament\Resources\CardOrders\CardOrderResource;
use Filament\Resources\Pages\ViewRecord;

class ViewCardOrder extends ViewRecord
{
    protected static string $resource = CardOrderResource::class;

    protected function getHeaderActions(): array
    {
        return CardOrderActions::all();
    }
}
