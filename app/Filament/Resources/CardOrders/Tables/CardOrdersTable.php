<?php

namespace App\Filament\Resources\CardOrders\Tables;

use App\Enums\CardNetwork;
use App\Enums\CardOrderStatus;
use App\Enums\LedgerAsset;
use App\Filament\Resources\CardOrders\CardOrderActions;
use App\Models\CardOrder;
use Filament\Actions\ActionGroup;
use Filament\Actions\ViewAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class CardOrdersTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->modifyQueryUsing(fn ($query) => $query->with('user'))
            ->columns([
                TextColumn::make('reference')
                    ->fontFamily('mono')
                    ->copyable()
                    ->searchable(),

                TextColumn::make('user.name')
                    ->label('Customer')
                    ->description(fn (CardOrder $record) => $record->user?->email)
                    ->searchable(['name', 'email']),

                TextColumn::make('card_name')
                    ->label('Card')
                    ->description(fn (CardOrder $record) => $record->network->label().' · '.$record->form->label()),

                TextColumn::make('fee')
                    ->money('USD')
                    ->sortable(),

                TextColumn::make('paid_from')
                    ->label('Paid from')
                    ->formatStateUsing(fn ($state) => LedgerAsset::from($state)->label())
                    ->toggleable(isToggledHiddenByDefault: true),

                TextColumn::make('status')
                    ->badge(),

                TextColumn::make('created_at')
                    ->label('Ordered')
                    ->since()
                    ->dateTimeTooltip()
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')->options(CardOrderStatus::class),
                SelectFilter::make('network')->options(CardNetwork::class),
            ])
            ->recordActions([
                ActionGroup::make([
                    ViewAction::make(),
                    ...CardOrderActions::all(),
                ]),
            ]);
    }
}
