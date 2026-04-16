<?php

namespace App\Filament\Resources\DailyResidualBonuses;

use App\Filament\Resources\DailyResidualBonuses\Pages\CreateDailyResidualBonuses;
use App\Filament\Resources\DailyResidualBonuses\Pages\EditDailyResidualBonuses;
use App\Filament\Resources\DailyResidualBonuses\Pages\ListDailyResidualBonuses;
use App\Filament\Resources\DailyResidualBonuses\Schemas\DailyResidualBonusesForm;
use App\Filament\Resources\DailyResidualBonuses\Tables\DailyResidualBonusesTable;
use App\Models\DailyResidualBonus;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class DailyResidualBonusesResource extends Resource
{
    protected static ?string $model = DailyResidualBonus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    public static function form(Schema $schema): Schema
    {
        return DailyResidualBonusesForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DailyResidualBonusesTable::configure($table);
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
            'index' => ListDailyResidualBonuses::route('/'),
            'create' => CreateDailyResidualBonuses::route('/create'),
            'edit' => EditDailyResidualBonuses::route('/{record}/edit'),
        ];
    }
}
