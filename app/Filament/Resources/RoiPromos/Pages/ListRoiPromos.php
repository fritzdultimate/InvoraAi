<?php

namespace App\Filament\Resources\RoiPromos\Pages;

use App\Filament\Resources\RoiPromos\RoiPromoResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListRoiPromos extends ListRecords
{
    protected static string $resource = RoiPromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make()->label('New promo'),
        ];
    }
}
