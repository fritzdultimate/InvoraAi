<?php

namespace App\Filament\Resources\RoiPromos\Pages;

use App\Filament\Resources\RoiPromos\RoiPromoResource;
use Filament\Resources\Pages\CreateRecord;

class CreateRoiPromo extends CreateRecord
{
    protected static string $resource = RoiPromoResource::class;

    protected function getRedirectUrl(): string
    {
        return $this->getResource()::getUrl('index');
    }
}
