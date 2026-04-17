<?php

namespace App\Filament\Resources\ReferralLevels;

use App\Filament\Resources\ReferralLevels\Pages\CreateReferralLevel;
use App\Filament\Resources\ReferralLevels\Pages\EditReferralLevel;
use App\Filament\Resources\ReferralLevels\Pages\ListReferralLevels;
use App\Filament\Resources\ReferralLevels\Schemas\ReferralLevelForm;
use App\Filament\Resources\ReferralLevels\Tables\ReferralLevelsTable;
use App\Models\ReferralLevel;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReferralLevelResource extends Resource
{
    protected static ?string $model = ReferralLevel::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;
    protected static  UnitEnum|string|null $navigationGroup = 'Rewards & Network';

    public static function form(Schema $schema): Schema
    {
        return ReferralLevelForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReferralLevelsTable::configure($table);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function canCreate(): bool {
        return ReferralLevel::count() < 10;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListReferralLevels::route('/'),
            'create' => CreateReferralLevel::route('/create'),
            'edit' => EditReferralLevel::route('/{record}/edit'),
        ];
    }
}
