<?php

namespace App\Filament\Resources\RoiPromos\Tables;

use App\Models\RoiPromo;
use Filament\Actions\Action;
use Filament\Actions\EditAction;
use Filament\Notifications\Notification;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class RoiPromosTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('starts_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query
                ->withCount('investments')
                ->withSum('investments', 'roi_boost_earned')
                ->withSum('investments', 'amount'))
            ->columns([
                TextColumn::make('name')
                    ->searchable()
                    ->weight('bold')
                    ->description(fn (RoiPromo $record) => $record->botNames()),

                TextColumn::make('status')
                    ->state(fn (RoiPromo $record) => $record->status())
                    ->badge()
                    ->formatStateUsing(fn ($state) => ucfirst($state))
                    ->color(fn ($state) => match ($state) {
                        'live' => 'success',
                        'scheduled' => 'info',
                        'ended' => 'gray',
                        default => 'warning',
                    }),

                TextColumn::make('multiplier')
                    ->label('Return')
                    ->formatStateUsing(fn (RoiPromo $record) => $record->multiplierLabel())
                    ->sortable(),

                TextColumn::make('starts_at')
                    ->label('Window')
                    ->dateTime('M d, H:i')
                    ->description(fn (RoiPromo $record) => 'to ' . $record->ends_at->format('M d, H:i'))
                    ->sortable(),

                TextColumn::make('investments_count')
                    ->label('Boosted positions')
                    ->formatStateUsing(fn ($state, RoiPromo $record) => $record->max_entries
                        ? $state . ' / ' . $record->max_entries
                        : (string) $state),

                TextColumn::make('investments_sum_amount')
                    ->label('Capital deployed')
                    ->money('USD')
                    ->default(0),

                TextColumn::make('investments_sum_roi_boost_earned')
                    ->label('Extra ROI paid')
                    ->money('USD')
                    ->default(0)
                    ->color('success'),

                IconColumn::make('show_banner')
                    ->label('Banner')
                    ->boolean(),
            ])
            ->recordActions([
                EditAction::make(),

                Action::make('endNow')
                    ->label('End now')
                    ->icon('heroicon-o-stop-circle')
                    ->color('danger')
                    ->visible(fn (RoiPromo $record) => $record->isLive())
                    ->requiresConfirmation()
                    ->modalDescription('New positions stop getting the boost right away. Positions already boosted keep it.')
                    ->action(function (RoiPromo $record) {
                        $record->update(['ends_at' => now()]);

                        Notification::make()->title('Promo ended')->success()->send();
                    }),
            ]);
    }
}
