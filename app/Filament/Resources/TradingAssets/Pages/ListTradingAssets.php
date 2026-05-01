<?php

namespace App\Filament\Resources\TradingAssets\Pages;

use App\Filament\Resources\TradingAssets\TradingAssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListTradingAssets extends ListRecords
{
    protected static string $resource = TradingAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
