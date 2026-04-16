<?php

namespace App\Filament\Resources\BotTerminations;

use App\Filament\Resources\BotTerminations\Pages\CreateBotTermination;
use App\Filament\Resources\BotTerminations\Pages\EditBotTermination;
use App\Filament\Resources\BotTerminations\Pages\ListBotTerminations;
use App\Filament\Resources\BotTerminations\Schemas\BotTerminationForm;
use App\Filament\Resources\BotTerminations\Tables\BotTerminationsTable;
use App\Models\BotTermination;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class BotTerminationResource extends Resource {
    protected static ?string $model = BotTermination::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?string $navigationLabel = 'Investment Terminations';

    public static function form(Schema $schema): Schema
    {
        return BotTerminationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return BotTerminationsTable::configure($table);
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
            'index' => ListBotTerminations::route('/'),
            'create' => CreateBotTermination::route('/create'),
            'edit' => EditBotTermination::route('/{record}/edit'),
        ];
    }

    public static function getRecordRouteBindingEloquentQuery(): Builder
    {
        return parent::getRecordRouteBindingEloquentQuery()
            ->withoutGlobalScopes([
                SoftDeletingScope::class,
            ]);
    }
}
