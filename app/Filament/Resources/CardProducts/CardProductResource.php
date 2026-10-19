<?php

namespace App\Filament\Resources\CardProducts;

use App\Filament\Resources\CardProducts\Pages\CreateCardProduct;
use App\Filament\Resources\CardProducts\Pages\EditCardProduct;
use App\Filament\Resources\CardProducts\Pages\ListCardProducts;
use App\Filament\Resources\CardProducts\Schemas\CardProductForm;
use App\Filament\Resources\CardProducts\Tables\CardProductsTable;
use App\Models\CardProduct;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CardProductResource extends Resource
{
    protected static ?string $model = CardProduct::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;
    protected static UnitEnum|string|null $navigationGroup = 'Cards';
    protected static ?string $navigationLabel = 'Card products';
    protected static ?string $modelLabel = 'card product';
    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return CardProductForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CardProductsTable::configure($table);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCardProducts::route('/'),
            'create' => CreateCardProduct::route('/create'),
            'edit' => EditCardProduct::route('/{record}/edit'),
        ];
    }
}
