<?php

namespace App\Filament\Resources\TradingAssets\Pages;

use App\Filament\Resources\TradingAssets\TradingAssetResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditTradingAsset extends EditRecord
{
    protected static string $resource = TradingAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
