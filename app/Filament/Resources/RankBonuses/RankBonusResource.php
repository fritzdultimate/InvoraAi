<?php

namespace App\Filament\Resources\RankBonuses;

use App\Filament\Resources\RankBonuses\Pages\CreateRankBonus;
use App\Filament\Resources\RankBonuses\Pages\EditRankBonus;
use App\Filament\Resources\RankBonuses\Pages\ListRankBonuses;
use App\Filament\Resources\RankBonuses\Schemas\RankBonusForm;
use App\Filament\Resources\RankBonuses\Tables\RankBonusesTable;
use App\Models\RankBonus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class RankBonusResource extends Resource
{
    protected static ?string $model = RankBonus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedTrophy;

    protected static ?string $recordTitleAttribute = 'Rank Bonuses';

    public static function getNavigationBadge(): ?string {
        return (string) RankBonus::where('status', 'locked')->count();
    }

    public static function form(Schema $schema): Schema
    {
        return RankBonusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return RankBonusesTable::configure($table);
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
            'index' => ListRankBonuses::route('/'),
            'create' => CreateRankBonus::route('/create'),
            'edit' => EditRankBonus::route('/{record}/edit'),
        ];
    }
}
