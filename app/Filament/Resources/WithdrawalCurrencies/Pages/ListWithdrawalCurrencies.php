<?php

namespace App\Filament\Resources\WithdrawalCurrencies\Pages;

use App\Filament\Resources\WithdrawalCurrencies\WithdrawalCurrencyResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListWithdrawalCurrencies extends ListRecords
{
    protected static string $resource = WithdrawalCurrencyResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
