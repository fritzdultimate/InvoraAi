<?php

namespace App\Filament\Resources\WithdrawalCurrencies;

use App\Filament\Resources\WithdrawalCurrencies\Pages\CreateWithdrawalCurrency;
use App\Filament\Resources\WithdrawalCurrencies\Pages\EditWithdrawalCurrency;
use App\Filament\Resources\WithdrawalCurrencies\Pages\ListWithdrawalCurrencies;
use App\Filament\Resources\WithdrawalCurrencies\Schemas\WithdrawalCurrencyForm;
use App\Filament\Resources\WithdrawalCurrencies\Tables\WithdrawalCurrenciesTable;
use App\Models\WithdrawalCurrency;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class WithdrawalCurrencyResource extends Resource
{
    protected static ?string $model = WithdrawalCurrency::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCurrencyDollar;
    // protected static ?string $navigationGroup = 'Finance';
    protected static ?string $label = 'Currency';
    protected static ?string $pluralLabel = 'Currencies';

    public static function form(Schema $schema): Schema
    {
        return WithdrawalCurrencyForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return WithdrawalCurrenciesTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => ListWithdrawalCurrencies::route('/'),
            'create' => CreateWithdrawalCurrency::route('/create'),
            'edit' => EditWithdrawalCurrency::route('/{record}/edit'),
        ];
    }
}
