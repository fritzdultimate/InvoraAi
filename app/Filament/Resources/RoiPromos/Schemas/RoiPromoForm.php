<?php

namespace App\Filament\Resources\RoiPromos\Schemas;

use App\Models\Bot;
use App\Models\RoiPromo;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\ToggleButtons;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Schema;

class RoiPromoForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Section::make('Promo')
                    ->description('Name it, pick the bots and set when it runs. Only positions opened inside this window get the boost.')
                    ->icon('heroicon-o-bolt')
                    ->columns(2)
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(80)
                            ->placeholder('Genius Double Up'),

                        Select::make('bot_ids')
                            ->label('Bots in the promo')
                            ->multiple()
                            ->required()
                            ->options(fn () => Bot::orderBy('price')->pluck('name', 'id'))
                            ->live(),

                        DateTimePicker::make('starts_at')
                            ->label('Starts')
                            ->required()
                            ->seconds(false)
                            ->live(),

                        DateTimePicker::make('ends_at')
                            ->label('Ends')
                            ->required()
                            ->seconds(false)
                            ->after('starts_at')
                            ->helperText('After this, new positions are not boosted. Positions already boosted keep their boost.'),

                        Toggle::make('is_active')
                            ->label('Promo switched on')
                            ->helperText('Off = draft. Nothing is boosted or shown while this is off.')
                            ->columnSpanFull(),
                    ]),

                Section::make('The return')
                    ->description('Positions that qualify are paid this multiple of the bot\'s normal return on every payout.')
                    ->icon('heroicon-o-arrow-trending-up')
                    ->columns(2)
                    ->schema([
                        TextInput::make('multiplier')
                            ->label('ROI multiplier')
                            ->numeric()
                            ->required()
                            ->default(2)
                            ->minValue(1.01)
                            // ->maxValue(10)
                            // ->step(0.05)
                            ->suffix('×')
                            ->live(onBlur: true)
                            ->helperText('2 = double the normal return. Changing this later only affects new positions.'),

                        ToggleButtons::make('boost_duration')
                            ->label('Boost lasts')
                            ->options([
                                'full' => 'Until the position matures',
                                'days' => 'A set number of days',
                            ])
                            ->default('full')
                            ->required()
                            ->inline()
                            ->live(),

                        TextInput::make('boost_days')
                            ->label('Number of days')
                            ->numeric()
                            ->minValue(1)
                            ->suffix('days')
                            ->required(fn (Get $get) => $get('boost_duration') === 'days')
                            ->visible(fn (Get $get) => $get('boost_duration') === 'days')
                            ->helperText('Counted from the day each position is opened.'),
                    ]),

                Section::make('Who can join')
                    ->description('Leave a field empty for no limit.')
                    ->icon('heroicon-o-user-group')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        ToggleButtons::make('audience')
                            ->label('Accounts')
                            ->options([
                                'all' => 'Everyone',
                                'new' => 'New sign-ups only',
                                'existing' => 'Existing users only',
                            ])
                            ->default('all')
                            ->required()
                            ->inline()
                            ->columnSpanFull()
                            ->helperText('New = accounts created after the promo starts.'),

                        Toggle::make('require_new_license')
                            ->label('License must be bought during the promo')
                            ->helperText('Purchases, renewals and upgrades made inside the window count.'),

                        Toggle::make('require_kyc')
                            ->label('KYC must be approved'),

                        TextInput::make('min_amount')
                            ->label('Minimum deployment')
                            ->numeric()
                            ->prefix('$'),

                        TextInput::make('max_amount')
                            ->label('Maximum deployment')
                            ->numeric()
                            ->prefix('$')
                            ->gte('min_amount'),

                        TextInput::make('max_per_user')
                            ->label('Boosted positions per user')
                            ->numeric()
                            ->minValue(1),

                        TextInput::make('max_entries')
                            ->label('Total boosted spots')
                            ->numeric()
                            ->minValue(1)
                            ->live(onBlur: true)
                            ->helperText('Limited spots show a "spots left" bar on the banner.'),
                    ]),

                Section::make('Dashboard banner')
                    ->description('A card at the top of every user\'s overview page.')
                    ->icon('heroicon-o-megaphone')
                    ->columns(2)
                    ->collapsible()
                    ->schema([
                        Toggle::make('show_banner')
                            ->label('Show banner on the dashboard')
                            ->default(true)
                            ->live()
                            ->columnSpanFull(),

                        Grid::make(2)
                            ->visible(fn (Get $get) => $get('show_banner'))
                            ->columnSpanFull()
                            ->schema([
                                ToggleButtons::make('banner_theme')
                                    ->label('Theme')
                                    ->options([
                                        'emerald' => 'Emerald',
                                        'gold' => 'Gold',
                                        'violet' => 'Violet',
                                    ])
                                    ->colors([
                                        'emerald' => 'success',
                                        'gold' => 'warning',
                                        'violet' => 'info',
                                    ])
                                    ->default('emerald')
                                    ->inline(),

                                TextInput::make('cta_label')
                                    ->label('Button text')
                                    ->placeholder('Deploy now')
                                    ->maxLength(30),

                                TextInput::make('headline')
                                    ->maxLength(90)
                                    ->columnSpanFull()
                                    ->placeholder(fn (Get $get) => self::draft($get)->headlineText())
                                    ->helperText('Leave empty to use the text shown in grey.'),

                                Textarea::make('description')
                                    ->rows(3)
                                    ->maxLength(300)
                                    ->columnSpanFull()
                                    ->placeholder(fn (Get $get) => self::draft($get)->descriptionText())
                                    ->helperText('Leave empty to use the text shown in grey.'),

                                Toggle::make('show_countdown')
                                    ->label('Show countdown')
                                    ->default(true),

                                Toggle::make('show_spots')
                                    ->label('Show spots left')
                                    ->default(true)
                                    ->visible(fn (Get $get) => filled($get('max_entries'))),

                                Toggle::make('show_before_start')
                                    ->label('Announce before it starts')
                                    ->helperText('Shows a "starts in" card while the promo is scheduled.'),

                                Toggle::make('dismissible')
                                    ->label('Users can hide it')
                                    ->default(true)
                                    ->helperText('Hidden until they log in again.'),
                            ]),
                    ]),

                Section::make('Email alerts')
                    ->icon('heroicon-o-envelope')
                    ->collapsible()
                    ->schema([
                        ToggleButtons::make('email_mode')
                            ->label('Email users when a boosted payout lands')
                            ->options([
                                'off' => 'Off',
                                'first' => 'First boosted payout only',
                                'every' => 'Every boosted payout',
                            ])
                            ->default('off')
                            ->required()
                            ->inline()
                            ->helperText('"Every" sends one email per payout cycle per position, which can be a lot of mail.'),
                    ]),
            ]);
    }

    // unsaved form values as a model, so the default banner text can be previewed
    private static function draft(Get $get): RoiPromo
    {
        return new RoiPromo([
            'multiplier' => $get('multiplier') ?: 2,
            'bot_ids' => $get('bot_ids') ?: [],
            'boost_duration' => $get('boost_duration') ?: 'full',
            'boost_days' => $get('boost_days'),
        ]);
    }
}
