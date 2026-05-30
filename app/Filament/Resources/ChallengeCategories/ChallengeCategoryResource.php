<?php

namespace App\Filament\Resources\ChallengeCategories;

use App\Filament\Resources\ChallengeCategories\Pages\CreateChallengeCategory;
use App\Filament\Resources\ChallengeCategories\Pages\EditChallengeCategory;
use App\Filament\Resources\ChallengeCategories\Pages\ListChallengeCategories;
use App\Filament\Resources\ChallengeCategories\Schemas\ChallengeCategoryForm;
use App\Filament\Resources\ChallengeCategories\Tables\ChallengeCategoriesTable;
use App\Models\ChallengeCategory;
use BackedEnum;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;

class ChallengeCategoryResource extends Resource
{
    protected static ?string $model = ChallengeCategory::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public static function form(Schema $schema): Schema
    {
        return ChallengeCategoryForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return ChallengeCategoriesTable::configure($table);
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
            'index' => ListChallengeCategories::route('/'),
            'create' => CreateChallengeCategory::route('/create'),
            'edit' => EditChallengeCategory::route('/{record}/edit'),
        ];
    }
}
