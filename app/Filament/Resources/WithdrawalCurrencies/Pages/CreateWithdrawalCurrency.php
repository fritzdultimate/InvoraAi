<?php

namespace App\Filament\Resources\WithdrawalCurrencies\Pages;

use App\Filament\Resources\WithdrawalCurrencies\WithdrawalCurrencyResource;
use Filament\Resources\Pages\CreateRecord;

class CreateWithdrawalCurrency extends CreateRecord
{
    protected static string $resource = WithdrawalCurrencyResource::class;
}
