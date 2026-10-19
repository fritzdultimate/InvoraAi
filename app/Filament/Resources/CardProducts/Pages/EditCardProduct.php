<?php

namespace App\Filament\Resources\CardProducts\Pages;

use App\Filament\Resources\CardProducts\CardProductResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditCardProduct extends EditRecord
{
    protected static string $resource = CardProductResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // products with orders can't be deleted (the foreign key blocks it); switch them off instead
            DeleteAction::make()->visible(fn () => ! $this->record->orders()->exists()),
        ];
    }
}
