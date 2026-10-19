<?php

namespace App\Filament\Pages;

use App\Models\CustomSetting;
use App\Services\Card\CardProgram;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\CheckboxList;
use Filament\Forms\Components\Radio;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\Toggle;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use UnitEnum;

class CardProgramSettings extends Page
{
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedAdjustmentsHorizontal;
    protected static UnitEnum|string|null $navigationGroup = 'Cards';
    protected static ?string $navigationLabel = 'Card program';
    protected static ?string $title = 'Card program';
    protected static ?int $navigationSort = 3;

    public ?array $data = [];

    public static function getNavigationBadge(): ?string
    {
        return CardProgram::isEnabled() ? null : 'Hidden';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        return 'gray';
    }

    public function mount(): void
    {
        $this->form->fill([
            'enabled' => CardProgram::isEnabled(),
            'program_open' => CardProgram::isOpen(),
            'require_kyc' => CardProgram::requiresKyc(),
            'require_active_license' => CardProgram::requiresActiveLicense(),
            'allowed_balances' => array_map(fn ($asset) => $asset->value, CardProgram::allowedBalances()),
            'cancel_until' => CardProgram::cancelUntil(),
            'notice' => CardProgram::notice(),
        ]);
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->statePath('data')
            ->components([
                Section::make('Cards for users')
                    ->description('Master switch. While this is off, the Cards page and menu link are hidden from every user.')
                    ->icon('heroicon-o-eye')
                    ->schema([
                        Toggle::make('enabled')
                            ->label('Show Cards to users')
                            ->onColor('success'),
                    ]),

                Section::make('Pre-orders')
                    ->icon('heroicon-o-credit-card')
                    ->columns(2)
                    ->schema([
                        Toggle::make('program_open')
                            ->label('Accept new pre-orders')
                            ->helperText('Off keeps the page visible but stops new orders. Existing orders keep moving.')
                            ->columnSpanFull(),

                        Toggle::make('require_kyc')
                            ->label('KYC must be approved'),

                        Toggle::make('require_active_license')
                            ->label('An active bot license is required'),
                    ]),

                Section::make('Payment and cancelling')
                    ->icon('heroicon-o-banknotes')
                    ->columns(2)
                    ->schema([
                        CheckboxList::make('allowed_balances')
                            ->label('Customers can pay from')
                            ->options(['main' => 'Main balance', 'deposit' => 'Deposit balance'])
                            ->required(),

                        Radio::make('cancel_until')
                            ->label('Customers can cancel (and get a refund)')
                            ->options([
                                'pending' => 'Only while under review',
                                'approved' => 'Until production starts',
                                'never' => 'Never (admins can still reject and refund)',
                            ])
                            ->required(),
                    ]),

                Section::make('Notice')
                    ->description('Shown at the top of the Cards page. Leave empty to hide it.')
                    ->icon('heroicon-o-megaphone')
                    ->schema([
                        Textarea::make('notice')
                            ->label('')
                            ->rows(2)
                            ->maxLength(250)
                            ->placeholder('Physical cards ship from November. Virtual cards are issued within 48 hours of approval.'),
                    ]),
            ]);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('form')
                ->livewireSubmitHandler('save')
                ->footer([
                    Actions::make([
                        Action::make('save')->label('Save settings')->submit('save'),
                    ]),
                ]),
        ]);
    }

    public function save(): void
    {
        $data = $this->form->getState();

        CustomSetting::set('cards.enabled', $data['enabled'] ? '1' : '0');
        CustomSetting::set('cards.program_open', $data['program_open'] ? '1' : '0');
        CustomSetting::set('cards.require_kyc', $data['require_kyc'] ? '1' : '0');
        CustomSetting::set('cards.require_active_license', $data['require_active_license'] ? '1' : '0');
        CustomSetting::set('cards.allowed_balances', implode(',', $data['allowed_balances']));
        CustomSetting::set('cards.cancel_until', $data['cancel_until']);
        CustomSetting::set('cards.notice', (string) ($data['notice'] ?? ''));

        Notification::make()->title('Card program saved')->success()->send();
    }
}
