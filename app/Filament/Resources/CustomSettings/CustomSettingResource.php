<?php

namespace App\Filament\Resources\CustomSettings;

use App\Filament\Resources\CustomSettings\Pages\CreateCustomSetting;
use App\Filament\Resources\CustomSettings\Pages\EditCustomSetting;
use App\Filament\Resources\CustomSettings\Pages\ListCustomSettings;
use App\Filament\Resources\CustomSettings\Schemas\CustomSettingForm;
use App\Filament\Resources\CustomSettings\Tables\CustomSettingsTable;
use App\Models\CustomSetting;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class CustomSettingResource extends Resource
{
    protected static ?string $model = CustomSetting::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog6Tooth;

    public static function form(Schema $schema): Schema
    {
        return CustomSettingForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return CustomSettingsTable::configure($table);
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
            'index' => ListCustomSettings::route('/'),
            'create' => CreateCustomSetting::route('/create'),
            'edit' => EditCustomSetting::route('/{record}/edit'),
        ];
    }
}
