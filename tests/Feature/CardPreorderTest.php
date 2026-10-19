<?php

use App\Enums\CardOrderStatus;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Mail\CardOrderUpdateMail;
use App\Models\CardOrder;
use App\Models\CardProduct;
use App\Models\CustomSetting;
use App\Models\User;
use App\Models\WalletLedger;
use App\Services\Card\CardOrderException;
use App\Services\Card\CardOrderService;
use App\Services\Wallet\WalletService;
use Illuminate\Support\Facades\Mail;

// The full migration set has MySQL-only SQL, so only the tables cards touch are built here.
beforeEach(function () {
    $this->artisan('migrate', ['--force' => true, '--path' => [
        'database/migrations/0001_01_01_000000_create_users_table.php',
        'database/migrations/2025_09_22_145432_add_two_factor_columns_to_users_table.php',
        'database/migrations/2026_02_03_231036_create_permission_tables.php',
        'database/migrations/2026_02_12_104309_create_bots_table.php',
        'database/migrations/2026_02_12_104716_create_bot_licenses_table.php',
        'database/migrations/2026_02_12_113851_create_wallet_ledgers_table.php',
        'database/migrations/2026_02_23_181120_add_balances_to_users_table.php',
        'database/migrations/2026_02_23_181615_add_locked_balance_to_users_table.php',
        'database/migrations/2026_02_26_123313_add_deposit_bonus_balance_to_users.php',
        'database/migrations/2026_02_26_124410_create_custom_settings_table.php',
        'database/migrations/2026_03_27_184151_create_notifications_table.php',
        'database/migrations/2026_03_27_185352_create_notification_user_table.php',
        'database/migrations/2026_03_27_212334_add_seen_at_to_notifications.php',
        'database/migrations/2026_03_08_132738_create_kyc_verifications_table.php',
        'database/migrations/2026_10_19_100000_create_card_tables.php',
    ]]);

    Mail::fake();
    \Spatie\Permission\Models\Role::findOrCreate('admin', 'web');

    $this->service = app(CardOrderService::class);

    $this->visa = CardProduct::create([
        'name' => 'Invora Visa', 'network' => 'visa', 'form' => 'physical', 'design' => 'sapphire',
        'preorder_fee' => 300, 'spending_limit' => 10000, 'limit_period' => 'monthly', 'max_per_user' => 1,
    ]);

    $this->virtual = CardProduct::create([
        'name' => 'Invora Virtual', 'network' => 'mastercard', 'form' => 'virtual', 'design' => 'gold',
        'preorder_fee' => 500, 'spending_limit' => 100000, 'limit_period' => 'monthly', 'max_per_user' => 1,
    ]);
});

function cardUser(float $main = 1000, string $kyc = 'approved'): User
{
    $user = User::forceCreate([
        'name' => 'Ada Obi',
        'email' => uniqid('ada').'@example.com',
        'password' => bcrypt('secret'),
        'kyc_status' => $kyc,
    ]);

    if ($main > 0) {
        WalletService::credit($user, $main, LedgerReference::DEPOSIT, null, 'test funds', LedgerAsset::MAIN);
    }

    return $user->fresh();
}

function address(): array
{
    return ['name_on_card' => 'Ada Obi', 'phone' => '+2348000000000', 'address_line1' => '1 Marina', 'city' => 'Lagos', 'state' => 'Lagos', 'country' => 'Nigeria'];
}

function mainBalance(User $user): float
{
    return (float) WalletService::getBalance($user, LedgerAsset::MAIN->value);
}

it('charges the fee and creates a pending order', function () {
    $user = cardUser(1000);

    $order = $this->service->placeOrder($user, $this->visa, LedgerAsset::MAIN, address());

    expect($order->status)->toBe(CardOrderStatus::PENDING)
        ->and($order->name_on_card)->toBe('ADA OBI')
        ->and((float) $order->fee)->toBe(300.0)
        ->and(mainBalance($user))->toBe(700.0)
        ->and(WalletLedger::where('reference_type', 'card_preorder')->where('reference_id', $order->id)->count())->toBe(1);

    Mail::assertSent(CardOrderUpdateMail::class);
});

it('requires KYC unless the admin switches it off', function () {
    $user = cardUser(1000, 'pending');

    expect(fn () => $this->service->placeOrder($user, $this->visa, LedgerAsset::MAIN, address()))
        ->toThrow(CardOrderException::class, 'KYC');

    CustomSetting::set('cards.require_kyc', '0');

    expect($this->service->placeOrder($user, $this->visa, LedgerAsset::MAIN, address())->status)->toBe(CardOrderStatus::PENDING);
});

it('refuses when the balance is too low and charges nothing', function () {
    $user = cardUser(100);

    expect(fn () => $this->service->placeOrder($user, $this->visa, LedgerAsset::MAIN, address()))
        ->toThrow(CardOrderException::class, 'too low');

    expect(CardOrder::count())->toBe(0)->and(mainBalance($user))->toBe(100.0);
});

it('stops new orders when the program is closed', function () {
    CustomSetting::set('cards.program_open', '0');

    expect(fn () => $this->service->placeOrder(cardUser(), $this->visa, LedgerAsset::MAIN, address()))
        ->toThrow(CardOrderException::class, 'closed');
});

it('only takes payment from allowed balances', function () {
    CustomSetting::set('cards.allowed_balances', 'deposit');

    expect(fn () => $this->service->placeOrder(cardUser(), $this->visa, LedgerAsset::MAIN, address()))
        ->toThrow(CardOrderException::class, 'balance');
});

it('enforces the per-customer limit, and a cancelled order frees it', function () {
    $user = cardUser(1000);
    $first = $this->service->placeOrder($user, $this->visa, LedgerAsset::MAIN, address());

    expect(fn () => $this->service->placeOrder($user, $this->visa, LedgerAsset::MAIN, address()))
        ->toThrow(CardOrderException::class, 'maximum');

    $this->service->cancelByUser($first, $user);

    expect($this->service->placeOrder($user, $this->visa, LedgerAsset::MAIN, address())->status)->toBe(CardOrderStatus::PENDING);
});

it('sells out when the slots are taken', function () {
    $this->visa->update(['stock' => 1]);
    $this->service->placeOrder(cardUser(), $this->visa, LedgerAsset::MAIN, address());

    expect(fn () => $this->service->placeOrder(cardUser(), $this->visa, LedgerAsset::MAIN, address()))
        ->toThrow(CardOrderException::class, 'sold out');
});

it('refunds a cancelled order exactly once', function () {
    $user = cardUser(1000);
    $order = $this->service->placeOrder($user, $this->visa, LedgerAsset::MAIN, address());

    $order = $this->service->cancelByUser($order, $user);

    expect($order->status)->toBe(CardOrderStatus::CANCELLED)
        ->and($order->refunded_at)->not->toBeNull()
        ->and(mainBalance($user))->toBe(1000.0);

    expect(fn () => $this->service->cancelByUser($order, $user))->toThrow(CardOrderException::class);
    expect(fn () => $this->service->reject($order, 'again'))->toThrow(CardOrderException::class);
    expect(mainBalance($user))->toBe(1000.0)
        ->and(WalletLedger::where('reference_type', 'card_preorder_refund')->count())->toBe(1);
});

it('follows the admin cancel window', function () {
    $user = cardUser(1000);
    $order = $this->service->placeOrder($user, $this->visa, LedgerAsset::MAIN, address());
    $order = $this->service->advance($order, CardOrderStatus::APPROVED);

    expect($this->service->canUserCancel($order))->toBeFalse();

    CustomSetting::set('cards.cancel_until', 'approved');
    expect($this->service->canUserCancel($order))->toBeTrue();

    CustomSetting::set('cards.cancel_until', 'never');
    expect(fn () => $this->service->cancelByUser($order, $user))->toThrow(CardOrderException::class);
});

it('does not let another customer cancel your order', function () {
    $order = $this->service->placeOrder(cardUser(), $this->visa, LedgerAsset::MAIN, address());

    expect(fn () => $this->service->cancelByUser($order, cardUser()))->toThrow(CardOrderException::class);
});

it('rejects with a refund and a reason', function () {
    $user = cardUser(1000);
    $order = $this->service->placeOrder($user, $this->visa, LedgerAsset::MAIN, address());

    $order = $this->service->reject($order, 'Address could not be verified.');

    expect($order->status)->toBe(CardOrderStatus::REJECTED)
        ->and($order->user_message)->toBe('Address could not be verified.')
        ->and(mainBalance($user))->toBe(1000.0);
});

it('moves a physical card through every step in order', function () {
    $order = $this->service->placeOrder(cardUser(), $this->visa, LedgerAsset::MAIN, address());

    expect(fn () => $this->service->advance($order, CardOrderStatus::SHIPPED))->toThrow(CardOrderException::class);

    $order = $this->service->advance($order, CardOrderStatus::APPROVED);
    $order = $this->service->advance($order, CardOrderStatus::PROCESSING);
    $order = $this->service->advance($order, CardOrderStatus::SHIPPED, ['courier' => 'DHL', 'tracking_number' => 'JD0001']);

    expect(fn () => $this->service->advance($order, CardOrderStatus::ACTIVE, ['last_four' => '12', 'expiry' => '13/30']))
        ->toThrow(CardOrderException::class, 'last 4');

    $order = $this->service->advance($order, CardOrderStatus::ACTIVE, ['last_four' => '4821', 'expiry' => '09/30']);

    expect($order->status)->toBe(CardOrderStatus::ACTIVE)
        ->and($order->tracking_number)->toBe('JD0001')
        ->and($order->last_four)->toBe('4821')
        ->and($order->shipped_at)->not->toBeNull();

    expect(fn () => $this->service->reject($order, 'too late'))->toThrow(CardOrderException::class);
});

it('skips shipping for virtual cards', function () {
    $order = $this->service->placeOrder(cardUser(), $this->virtual, LedgerAsset::MAIN, ['name_on_card' => 'Ada Obi']);

    $order = $this->service->advance($order, CardOrderStatus::APPROVED);
    $order = $this->service->advance($order, CardOrderStatus::PROCESSING);

    expect($this->service->nextStatus($order))->toBe(CardOrderStatus::ACTIVE);
    expect(fn () => $this->service->advance($order, CardOrderStatus::SHIPPED))->toThrow(CardOrderException::class);
});

it('walks the order sheet step by step and places the order', function () {
    $user = cardUser(1000);
    $this->actingAs($user);

    \Livewire\Livewire::test(\App\Livewire\Dashboard\Cards\Index::class)
        ->call('choose', $this->visa->id)
        ->assertSet('productId', $this->visa->id)
        ->set('name_on_card', '')
        ->call('next')
        ->assertHasErrors('name_on_card')
        ->set('name_on_card', 'Ada Obi')
        ->call('next')
        ->assertSet('step', 2)
        ->call('next')
        ->assertHasErrors(['address_line1', 'city', 'phone'])
        ->set('address_line1', '1 Marina')->set('city', 'Lagos')->set('state', 'Lagos')->set('country', 'Nigeria')->set('phone', '+2348000000000')
        ->call('next')
        ->assertSet('step', 3)
        ->call('next')
        ->assertSet('step', 4)
        ->call('place')
        ->assertHasErrors('agree')
        ->set('agree', true)
        ->call('place')
        ->assertRedirect(route('cards.order', CardOrder::first()));

    expect(CardOrder::first()->address_line1)->toBe('1 Marina')->and(mainBalance($user))->toBe(700.0);
});

it('blocks the sheet for users without KYC', function () {
    $this->actingAs(cardUser(1000, 'pending'));

    \Livewire\Livewire::test(\App\Livewire\Dashboard\Cards\Index::class)
        ->call('choose', $this->visa->id)
        ->assertSet('productId', null)
        ->assertDispatched('error');
});

it('shows an order only to its owner and lets them cancel', function () {
    $user = cardUser(1000);
    $order = $this->service->placeOrder($user, $this->visa, LedgerAsset::MAIN, address());

    $this->actingAs(cardUser());
    \Livewire\Livewire::test(\App\Livewire\Dashboard\Cards\Show::class, ['order' => $order])->assertNotFound();

    $this->actingAs($user);
    \Livewire\Livewire::test(\App\Livewire\Dashboard\Cards\Show::class, ['order' => $order])
        ->assertSee($order->reference)
        ->call('cancel');

    expect($order->fresh()->status)->toBe(CardOrderStatus::CANCELLED)->and(mainBalance($user))->toBe(1000.0);
});

it('renders the update email', function () {
    $order = $this->service->placeOrder(cardUser(), $this->visa, LedgerAsset::MAIN, address());

    $html = (new CardOrderUpdateMail($order, 'Pre-order received', 'We have it.'))->render();

    expect($html)->toContain($order->reference)->toContain('Track your card');
});

describe('admin panel', function () {
    beforeEach(function () {
        \Filament\Facades\Filament::setCurrentPanel('admin');
        $admin = cardUser(0);
        $admin->assignRole('admin');
        $this->actingAs($admin);
    });

    it('creates a card product', function () {
        \Livewire\Livewire::test(\App\Filament\Resources\CardProducts\Pages\CreateCardProduct::class)
            ->fillForm([
                'name' => 'Invora Platinum', 'network' => 'mastercard', 'form' => 'virtual', 'design' => 'platinum',
                'preorder_fee' => 750, 'spending_limit' => 250000, 'limit_period' => 'monthly', 'max_per_user' => 1,
                'features' => ['Concierge'],
            ])
            ->call('create')
            ->assertHasNoFormErrors();

        expect(CardProduct::where('name', 'Invora Platinum')->first()->features)->toBe(['Concierge']);
    });

    it('runs the order workflow from the table', function () {
        $order = $this->service->placeOrder(cardUser(), $this->visa, LedgerAsset::MAIN, address());
        $list = \Livewire\Livewire::test(\App\Filament\Resources\CardOrders\Pages\ListCardOrders::class);

        $list->set('activeTab', 'all')
            ->assertCanSeeTableRecords([$order])
            ->callTableAction('to_approved', $order, ['user_message' => 'Welcome aboard'])
            ->callTableAction('to_processing', $order)
            ->callTableAction('ship', $order, ['courier' => 'DHL', 'tracking_number' => 'JD0001'])
            ->callTableAction('activate', $order, ['last_four' => '4821', 'expiry' => '09/30']);

        expect($order->fresh()->status)->toBe(CardOrderStatus::ACTIVE);
    });

    it('rejects and refunds from the view page', function () {
        $user = cardUser(1000);
        $order = $this->service->placeOrder($user, $this->visa, LedgerAsset::MAIN, address());

        \Livewire\Livewire::test(\App\Filament\Resources\CardOrders\Pages\ViewCardOrder::class, ['record' => $order->getRouteKey()])
            ->assertSee($order->reference)
            ->callAction('reject', ['reason' => 'Unsupported country'])
            ->assertHasNoActionErrors();

        expect($order->fresh()->status)->toBe(CardOrderStatus::REJECTED)->and(mainBalance($user))->toBe(1000.0);
    });

    it('saves the program settings', function () {
        \Livewire\Livewire::test(\App\Filament\Pages\CardProgramSettings::class)
            ->set('data.program_open', false)
            ->set('data.allowed_balances', ['deposit'])
            ->set('data.cancel_until', 'approved')
            ->set('data.notice', 'Shipping starts in November.')
            ->call('save');

        expect(\App\Services\Card\CardProgram::isOpen())->toBeFalse()
            ->and(\App\Services\Card\CardProgram::cancelUntil())->toBe('approved')
            ->and(\App\Services\Card\CardProgram::allowedBalances())->toBe([LedgerAsset::DEPOSIT]);
    });
});
