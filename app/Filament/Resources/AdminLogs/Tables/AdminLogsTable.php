<?php

namespace App\Filament\Resources\AdminLogs\Tables;

use App\Models\AdminLog;
use App\Models\User;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\DatePicker;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\Filter;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class AdminLogsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('id', 'desc')
            ->columns([
                TextColumn::make('created_at')
                    ->label('When')
                    ->dateTime('M j, Y g:i A')
                    ->sortable(),
                TextColumn::make('user.email')
                    ->label('Admin')
                    ->placeholder('—')
                    ->searchable(),
                TextColumn::make('event')
                    ->badge()
                    ->color(fn (string $state) => match ($state) {
                        'created', 'login' => 'success',
                        'updated', 'sensitive_verified' => 'info',
                        'deleted', 'login_failed', 'sensitive_failed' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('description')
                    ->limit(60)
                    ->searchable(),
                TextColumn::make('ip_address')
                    ->label('IP')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('event')
                    ->options(fn () => AdminLog::query()->distinct()->orderBy('event')->pluck('event', 'event')->all()),
                SelectFilter::make('user_id')
                    ->label('Admin')
                    ->options(fn () => User::role('admin')->pluck('email', 'id')->all()),
                SelectFilter::make('subject_type')
                    ->label('Record type')
                    ->options(fn () => AdminLog::query()->whereNotNull('subject_type')->distinct()->pluck('subject_type')
                        ->mapWithKeys(fn ($type) => [$type => class_basename($type)])->all()),
                Filter::make('created_at')
                    ->schema([
                        DatePicker::make('from'),
                        DatePicker::make('until'),
                    ])
                    ->query(fn (Builder $query, array $data) => $query
                        ->when($data['from'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '>=', $date))
                        ->when($data['until'] ?? null, fn ($q, $date) => $q->whereDate('created_at', '<=', $date))),
            ])
            ->recordActions([
                ViewAction::make(),
            ]);
    }
}
