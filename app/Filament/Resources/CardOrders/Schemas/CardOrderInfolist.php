<?php

namespace App\Filament\Resources\CardOrders\Schemas;

use App\Enums\LedgerAsset;
use App\Models\CardOrder;
use Filament\Infolists\Components\TextEntry;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CardOrderInfolist
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(3)
            ->components([
                Section::make('Order')
                    ->columnSpan(2)
                    ->columns(2)
                    ->schema([
                        TextEntry::make('reference')->fontFamily('mono')->copyable(),
                        TextEntry::make('status')->badge(),
                        TextEntry::make('card_name')->label('Card'),
                        TextEntry::make('network')->formatStateUsing(fn ($state, CardOrder $record) => $state->label().' · '.$record->form->label()),
                        TextEntry::make('fee')->money('USD'),
                        TextEntry::make('paid_from')->formatStateUsing(fn ($state) => LedgerAsset::from($state)->label()),
                        TextEntry::make('spending_limit')->label('Card limit')->formatStateUsing(fn ($state, CardOrder $record) => $record->limitLabel()),
                        TextEntry::make('name_on_card')->fontFamily('mono'),
                        TextEntry::make('user_message')->label('Last message to customer')->columnSpanFull()->placeholder('None'),
                        TextEntry::make('admin_note')->label('Internal note')->columnSpanFull()->placeholder('None'),
                    ]),

                Section::make('Customer')
                    ->columnSpan(1)
                    ->schema([
                        TextEntry::make('user.name')->label('Name'),
                        TextEntry::make('user.email')->label('Email')->copyable(),
                        TextEntry::make('user.kyc_status')->label('KYC')->badge()->color(fn ($state) => $state === 'approved' ? 'success' : 'warning'),
                    ]),

                Section::make('Delivery')
                    ->columnSpan(2)
                    ->columns(2)
                    ->visible(fn (CardOrder $record) => $record->isPhysical())
                    ->schema([
                        TextEntry::make('address')->state(fn (CardOrder $record) => $record->shippingAddress())->columnSpanFull()->copyable(),
                        TextEntry::make('phone')->copyable(),
                        TextEntry::make('courier')->placeholder('Not shipped'),
                        TextEntry::make('tracking_number')->fontFamily('mono')->copyable()->placeholder('—'),
                        TextEntry::make('tracking_url')->label('Tracking link')->url(fn ($state) => $state, true)->placeholder('—'),
                    ]),

                Section::make('Card')
                    ->columnSpan(1)
                    ->visible(fn (CardOrder $record) => $record->last_four !== null)
                    ->schema([
                        TextEntry::make('last_four')->label('Number')->formatStateUsing(fn ($state) => '•••• '.$state)->fontFamily('mono'),
                        TextEntry::make('expiry'),
                    ]),

                Section::make('Timeline')
                    ->columnSpanFull()
                    ->columns(4)
                    ->schema([
                        TextEntry::make('created_at')->label('Ordered')->dateTime(),
                        TextEntry::make('approved_at')->dateTime()->placeholder('—'),
                        TextEntry::make('processing_at')->label('Production')->dateTime()->placeholder('—'),
                        TextEntry::make('shipped_at')->dateTime()->placeholder('—'),
                        TextEntry::make('activated_at')->dateTime()->placeholder('—'),
                        TextEntry::make('cancelled_at')->dateTime()->placeholder('—'),
                        TextEntry::make('rejected_at')->dateTime()->placeholder('—'),
                        TextEntry::make('refunded_at')->dateTime()->placeholder('—'),
                    ]),
            ]);
    }
}
