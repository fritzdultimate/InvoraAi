<?php

namespace App\Filament\Resources\AdminLogs;

use App\Filament\Resources\AdminLogs\Pages\ListAdminLogs;
use App\Filament\Resources\AdminLogs\Pages\ViewAdminLog;
use App\Filament\Resources\AdminLogs\Tables\AdminLogsTable;
use App\Models\AdminLog;
use BackedEnum;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use UnitEnum;

class AdminLogResource extends Resource
{
    protected static ?string $model = AdminLog::class;
    protected static ?string $navigationLabel = 'Admin Logs';
    protected static ?string $modelLabel = 'Admin Log';
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;
    protected static UnitEnum|string|null $navigationGroup = 'Security';

    public static function table(Table $table): Table
    {
        return AdminLogsTable::configure($table);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make()
                ->columns(2)
                ->schema([
                    TextEntry::make('created_at')->label('When')->dateTime('M j, Y g:i:s A'),
                    TextEntry::make('user.email')->label('Admin')->placeholder('—'),
                    TextEntry::make('event')->badge(),
                    TextEntry::make('subject')
                        ->state(fn (AdminLog $record) => $record->subject_type ? class_basename($record->subject_type) . ' #' . $record->subject_id : null)
                        ->placeholder('—'),
                    TextEntry::make('description')->columnSpanFull()->placeholder('—'),
                    TextEntry::make('ip_address')->label('IP'),
                    TextEntry::make('url')->label('URL'),
                    TextEntry::make('user_agent')->columnSpanFull(),
                ]),
            Section::make('Before')
                ->schema([
                    TextEntry::make('old_values')
                        ->hiddenLabel()
                        ->state(fn (AdminLog $record) => self::pretty($record->old_values))
                        ->html(),
                ]),
            Section::make('After')
                ->schema([
                    TextEntry::make('new_values')
                        ->hiddenLabel()
                        ->state(fn (AdminLog $record) => self::pretty($record->new_values))
                        ->html(),
                ]),
        ]);
    }

    private static function pretty(?array $values): string
    {
        if (empty($values)) {
            return '—';
        }

        return '<pre style="white-space: pre-wrap; font-size: 12px;">' . e(json_encode($values, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES)) . '</pre>';
    }

    // the log is read-only, even for admins
    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit(Model $record): bool
    {
        return false;
    }

    public static function canDelete(Model $record): bool
    {
        return false;
    }

    public static function canDeleteAny(): bool
    {
        return false;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAdminLogs::route('/'),
            'view' => ViewAdminLog::route('/{record}'),
        ];
    }
}
