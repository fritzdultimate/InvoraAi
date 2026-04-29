<?php

namespace App\Filament\Resources\BotInvestments;

use App\Filament\Resources\BotInvestments\Pages\CreateBotInvestment;
use App\Filament\Resources\BotInvestments\Pages\EditBotInvestment;
use App\Filament\Resources\BotInvestments\Pages\ListBotInvestments;
use App\Filament\Resources\BotInvestments\Schemas\BotInvestmentForm;
use App\Filament\Resources\BotInvestments\Tables\BotInvestmentsTable;
use App\Models\BotInvestment;
use BackedEnum;
use UnitEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class BotInvestmentResource extends Resource
{
    protected static ?string $model = BotInvestment::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedDocumentCurrencyDollar;

    protected static  UnitEnum|string|null $navigationGroup = 'Investment System';

    public static function form(Schema $schema): Schema
    {
        return BotInvestmentForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BotInvestmentsTable::configure($table);
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
            'index' => ListBotInvestments::route('/'),
            'create' => CreateBotInvestment::route('/create'),
            'edit' => EditBotInvestment::route('/{record}/edit'),
        ];
    }
}
