<?php

namespace App\Filament\Resources\ReferralBonuses;

use App\Filament\Resources\ReferralBonuses\Pages\CreateReferralBonus;
use App\Filament\Resources\ReferralBonuses\Pages\EditReferralBonus;
use App\Filament\Resources\ReferralBonuses\Pages\ListReferralBonuses;
use App\Filament\Resources\ReferralBonuses\Schemas\ReferralBonusForm;
use App\Filament\Resources\ReferralBonuses\Tables\ReferralBonusesTable;
use App\Models\ReferralBonus;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ReferralBonusResource extends Resource
{
    protected static ?string $model = ReferralBonus::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;
    protected static  UnitEnum|string|null $navigationGroup = 'Rewards & Network';

    protected static ?string $recordTitleAttribute = 'Referral Rewards';

    public static function form(Schema $schema): Schema
    {
        return ReferralBonusForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ReferralBonusesTable::configure($table);
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
            'index' => ListReferralBonuses::route('/'),
            'create' => CreateReferralBonus::route('/create'),
            'edit' => EditReferralBonus::route('/{record}/edit'),
        ];
    }
}
