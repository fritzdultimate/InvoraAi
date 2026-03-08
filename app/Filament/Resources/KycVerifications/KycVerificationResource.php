<?php

namespace App\Filament\Resources\KycVerifications;

use App\Filament\Resources\KycVerifications\Pages\CreateKycVerification;
use App\Filament\Resources\KycVerifications\Pages\EditKycVerification;
use App\Filament\Resources\KycVerifications\Pages\ListKycVerifications;
use App\Filament\Resources\KycVerifications\Schemas\KycVerificationForm;
use App\Filament\Resources\KycVerifications\Tables\KycVerificationsTable;
use App\Models\KycVerification;
use BackedEnum;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

class KycVerificationResource extends Resource
{
    protected static ?string $model = KycVerification::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentCheck;

    public static function form(Schema $schema): Schema
    {
        return KycVerificationForm::configure($schema);
    }

    public static function table(Table $table): Table
    {
        return KycVerificationsTable::configure($table);
    }

    public static function getNavigationBadge(): ?string {
        return (string) KycVerification::where('status', 'pending')->count();
    }

    public static function getNavigationBadgeColor(): ?string {
        return 'danger';
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
            'index' => ListKycVerifications::route('/'),
            'create' => CreateKycVerification::route('/create'),
            'edit' => EditKycVerification::route('/{record}/edit'),
        ];
    }
}
