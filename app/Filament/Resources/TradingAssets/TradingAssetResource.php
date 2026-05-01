<?php

namespace App\Filament\Resources\TradingAssets;

use App\Filament\Resources\TradingAssets\Pages\CreateTradingAsset;
use App\Filament\Resources\TradingAssets\Pages\EditTradingAsset;
use App\Filament\Resources\TradingAssets\Pages\ListTradingAssets;
use App\Filament\Resources\TradingAssets\Schemas\TradingAssetForm;
use App\Filament\Resources\TradingAssets\Tables\TradingAssetsTable;
use App\Models\TradingAsset;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class TradingAssetResource extends Resource
{
    protected static ?string $model = TradingAsset::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return TradingAssetForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return TradingAssetsTable::configure($table);
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
            'index' => ListTradingAssets::route('/'),
            'create' => CreateTradingAsset::route('/create'),
            'edit' => EditTradingAsset::route('/{record}/edit'),
        ];
    }
}
