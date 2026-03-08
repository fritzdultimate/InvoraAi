<?php

namespace App\Filament\Resources\Deposits;

use App\Enums\DepositStatus;
use App\Filament\Resources\Deposits\Pages\CreateDeposit;
use App\Filament\Resources\Deposits\Pages\EditDeposit;
use App\Filament\Resources\Deposits\Pages\ListDeposits;
use App\Filament\Resources\Deposits\Schemas\DepositForm;
use App\Filament\Resources\Deposits\Tables\DepositsTable;
use App\Models\Deposit;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\Relation;

class DepositResource extends Resource
{
    protected static ?string $model = Deposit::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedBanknotes;



    public static function canCreate(): bool
    {
        return false;
    }

    // public static function getEloquentQuery(): Builder
    // {
    //     return parent::getEloquentQuery()
    //         ->where('override', false);
    // }

    public static function getNavigationBadge(): ?string {
        return (string) Deposit::where('status', 'pending')->count() + Deposit::where('status', 'waiting')->count();
    }

    public static function getNavigationBadgeColor(): ?string {
        return 'warning';
    }

    public static function form(Schema $schema): Schema {
        return DepositForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return DepositsTable::configure($table);
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
            'index' => ListDeposits::route('/'),
            'create' => CreateDeposit::route('/create'),
            'edit' => EditDeposit::route('/{record}/edit'),
        ];
    }
}
