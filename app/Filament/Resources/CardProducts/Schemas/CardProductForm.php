<?php

namespace App\Filament\Resources\CardProducts\Schemas;

use App\Enums\CardNetwork;
use App\Models\CardProduct;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TagsInput;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class CardProductForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Card')
                    ->description('What the customer sees on the Cards page.')
                    ->icon('heroicon-o-credit-card')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(60)
                            ->placeholder('Invora Visa Classic'),

                        TextInput::make('tagline')
                            ->maxLength(120)
                            ->placeholder('Everyday spending, worldwide'),

                        ToggleButtons::make('network')
                            ->options(CardNetwork::class)
                            ->required()
                            ->inline(),

                        ToggleButtons::make('form')
                            ->label('Card type')
                            ->options(CardProduct::FORMS)
                            ->icons([
                                'physical' => 'heroicon-o-truck',
                                'virtual' => 'heroicon-o-device-phone-mobile',
                                'both' => 'heroicon-o-squares-2x2',
                            ])
                            ->default('physical')
                            ->required()
                            ->inline()
                            ->columnSpanFull()
                            ->helperText('Physical cards are shipped to an address. Virtual cards go live in the dashboard. With "Both", the customer picks when ordering.'),

                        Select::make('design')
                            ->options(CardProduct::DESIGNS)
                            ->default('obsidian')
                            ->required()
                            ->native(false),

                        TextInput::make('delivery_estimate')
                            ->label('Delivery estimate')
                            ->maxLength(60)
                            ->placeholder('10–15 business days')
                            ->helperText('For "Both", e.g. "Virtual instantly, physical in 10–15 days".'),
                    ]),

                Section::make('Price and limit')
                    ->icon('heroicon-o-banknotes')
                    ->columns(3)
                    ->schema([
                        TextInput::make('preorder_fee')
                            ->label('Pre-order fee')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->prefix('$')
                            ->helperText('Charged at order, refunded if cancelled or rejected.'),

                        TextInput::make('spending_limit')
                            ->label('Card limit')
                            ->numeric()
                            ->required()
                            ->minValue(1)
                            ->prefix('$'),

                        Select::make('limit_period')
                            ->label('Limit applies')
                            ->options(CardProduct::LIMIT_PERIODS)
                            ->default('monthly')
                            ->required()
                            ->native(false),
                    ]),

                Section::make('Availability')
                    ->icon('heroicon-o-adjustments-horizontal')
                    ->columns(2)
                    ->schema([
                        Toggle::make('is_active')
                            ->label('Open for pre-order')
                            ->default(true)
                            ->helperText('Off hides the card. Existing orders are not affected.'),

                        Toggle::make('is_featured')
                            ->label('Highlight as most popular'),

                        TextInput::make('stock')
                            ->label('Pre-order slots')
                            ->numeric()
                            ->minValue(1)
                            ->helperText('Leave empty for no limit. Cancelled and rejected orders free their slot.'),

                        TextInput::make('max_per_user')
                            ->label('Max per customer')
                            ->numeric()
                            ->minValue(1)
                            ->default(1)
                            ->required(),

                        TextInput::make('sort_order')
                            ->label('Order on page')
                            ->numeric()
                            ->default(0)
                            ->helperText('Lower shows first.'),
                    ]),

                Section::make('Highlights')
                    ->description('Short points listed on the card. Press Enter after each one.')
                    ->icon('heroicon-o-sparkles')
                    ->schema([
                        TagsInput::make('features')
                            ->label('')
                            ->placeholder('Contactless payments')
                            ->reorderable(),
                    ]),
            ]);
    }
}
