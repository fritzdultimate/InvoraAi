<?php

namespace App\Filament\Resources\CardProducts\Tables;

use App\Enums\CardOrderStatus;
use App\Models\CardProduct;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;

class CardProductsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->modifyQueryUsing(fn ($query) => $query->withCount([
                'orders as live_orders_count' => fn ($q) => $q->whereNotIn('status', [CardOrderStatus::CANCELLED->value, CardOrderStatus::REJECTED->value]),
            ]))
            ->columns([
                TextColumn::make('name')
                    ->weight('bold')
                    ->description(fn (CardProduct $record) => $record->tagline)
                    ->searchable(),

                TextColumn::make('network')
                    ->badge()
                    ->formatStateUsing(fn ($state) => $state->label())
                    ->color(fn ($state) => $state->value === 'visa' ? 'info' : 'warning'),

                TextColumn::make('form')
                    ->label('Type')
                    ->badge()
                    ->color('gray')
                    ->formatStateUsing(fn ($state, CardProduct $record) => $record->formLabel()),

                TextColumn::make('preorder_fee')
                    ->label('Fee')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('spending_limit')
                    ->label('Limit')
                    ->formatStateUsing(fn ($state, CardProduct $record) => $record->limitLabel()),

                TextColumn::make('live_orders_count')
                    ->label('Orders')
                    ->formatStateUsing(fn ($state, CardProduct $record) => $record->stock ? "{$state} / {$record->stock}" : $state),

                ToggleColumn::make('is_active')
                    ->label('Open'),
            ])
            ->recordActions([
                EditAction::make(),
            ]);
    }
}
