<?php

namespace App\Filament\Resources\ChallengeEntries;

use App\Filament\Resources\ChallengeEntries\Pages\CreateChallengeEntry;
use App\Filament\Resources\ChallengeEntries\Pages\EditChallengeEntry;
use App\Filament\Resources\ChallengeEntries\Pages\ListChallengeEntries;
use App\Filament\Resources\ChallengeEntries\Schemas\ChallengeEntryForm;
use App\Filament\Resources\ChallengeEntries\Tables\ChallengeEntriesTable;
use App\Models\ChallengeEntry;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class ChallengeEntryResource extends Resource
{
    protected static ?string $model = ChallengeEntry::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ChallengeEntryForm::configure($schema);
    }

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()
            ->where('phase', 2);
    }

    public static function table(Table $table): Table
    {
        return ChallengeEntriesTable::configure($table);
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
            'index' => ListChallengeEntries::route('/'),
            'create' => CreateChallengeEntry::route('/create'),
            'edit' => EditChallengeEntry::route('/{record}/edit'),
        ];
    }
}
