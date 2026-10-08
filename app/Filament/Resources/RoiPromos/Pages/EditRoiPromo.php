<?php

namespace App\Filament\Resources\RoiPromos\Pages;

use App\Filament\Resources\RoiPromos\RoiPromoResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditRoiPromo extends EditRecord
{
    protected static string $resource = RoiPromoResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // a promo that already boosted positions can't be deleted, only ended
            DeleteAction::make()->hidden(fn () => $this->record->investments()->exists()),
        ];
    }
}
