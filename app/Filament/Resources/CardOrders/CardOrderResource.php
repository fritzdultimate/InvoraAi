<?php

namespace App\Filament\Resources\CardOrders;

use App\Enums\CardOrderStatus;
use App\Filament\Resources\CardOrders\Pages\ListCardOrders;
use App\Filament\Resources\CardOrders\Pages\ViewCardOrder;
use App\Filament\Resources\CardOrders\Schemas\CardOrderInfolist;
use App\Filament\Resources\CardOrders\Tables\CardOrdersTable;
use App\Models\CardOrder;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use UnitEnum;

class CardOrderResource extends Resource
{
    protected static ?string $model = CardOrder::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedInboxStack;
    protected static UnitEnum|string|null $navigationGroup = 'Cards';
    protected static ?string $navigationLabel = 'Card orders';
    protected static ?string $modelLabel = 'card order';
    protected static ?string $recordTitleAttribute = 'reference';
    protected static ?int $navigationSort = 1;

    public static function infolist(Schema $schema): Schema
    {
        return CardOrderInfolist::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CardOrdersTable::configure($table);
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationBadge(): ?string
    {
        $pending = CardOrder::where('status', CardOrderStatus::PENDING->value)->count();

        return $pending ? (string) $pending : null;
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'warning';
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCardOrders::route('/'),
            'view' => ViewCardOrder::route('/{record}'),
        ];
    }
}
