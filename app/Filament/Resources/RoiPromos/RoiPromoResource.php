<?php

namespace App\Filament\Resources\RoiPromos;

use App\Filament\Resources\RoiPromos\Pages\CreateRoiPromo;
use App\Filament\Resources\RoiPromos\Pages\EditRoiPromo;
use App\Filament\Resources\RoiPromos\Pages\ListRoiPromos;
use App\Filament\Resources\RoiPromos\Schemas\RoiPromoForm;
use App\Filament\Resources\RoiPromos\Tables\RoiPromosTable;
use App\Models\RoiPromo;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RoiPromoResource extends Resource
{
    protected static ?string $model = RoiPromo::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBolt;
    protected static UnitEnum|string|null $navigationGroup = 'Investment System';
    protected static ?string $navigationLabel = 'ROI Boost Promos';
    protected static ?string $modelLabel = 'ROI boost promo';

    public static function form(Schema $schema): Schema
    {
        return RoiPromoForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RoiPromosTable::configure($table);
    }

    public static function getNavigationBadge(): ?string
    {
        return RoiPromo::live()->exists() ? 'Live' : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'success';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListRoiPromos::route('/'),
            'create' => CreateRoiPromo::route('/create'),
            'edit' => EditRoiPromo::route('/{record}/edit'),
        ];
    }
}
