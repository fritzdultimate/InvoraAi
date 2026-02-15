<?php

namespace App\Filament\Resources\WithdrawalCurrencies\Pages;

use App\Filament\Resources\WithdrawalCurrencies\WithdrawalCurrencyResource;
use Filament\Actions\DeleteAction;
use Filament\Resources\Pages\EditRecord;

class EditWithdrawalCurrency extends EditRecord
{
    protected static string $resource = WithdrawalCurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            DeleteAction::make(),
        ];
    }
}
