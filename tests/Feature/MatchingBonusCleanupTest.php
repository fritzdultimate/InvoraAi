<?php

use App\Enums\BotInvestmentStatus;
use App\Enums\LedgerAsset;
use App\Enums\LedgerReference;
use App\Models\Bot;
use App\Models\BotInvestment;
use App\Models\BotLicense;
use App\Models\User;
use App\Models\WalletLedger;
use App\Services\MatchingBonusCleanupService;
use App\Services\Wallet\WalletService;

beforeEach(function () {
    $this->artisan('migrate', ['--force' => true, '--path' => [
        'database/migrations/0001_01_01_000000_create_users_table.php',
        'database/migrations/2025_09_22_145432_add_two_factor_columns_to_users_table.php',
        'database/migrations/2026_02_03_231036_create_permission_tables.php',
        'database/migrations/2026_02_12_104309_create_bots_table.php',
        'database/migrations/2026_02_12_104716_create_bot_licenses_table.php',
        'database/migrations/2026_02_12_105114_create_bot_investments_table.php',
        'database/migrations/2026_02_12_113851_create_wallet_ledgers_table.php',
        'database/migrations/2026_02_13_114814_add_price_to_bots_and_meta_to_bot_licenses.php',
        'database/migrations/2026_02_23_181120_add_balances_to_users_table.php',
        'database/migrations/2026_02_23_181615_add_locked_balance_to_users_table.php',
        'database/migrations/2026_02_26_123313_add_deposit_bonus_balance_to_users.php',
        'database/migrations/2026_04_10_224106_add_uuid_to_bot_investments_table.php',
        'database/migrations/2026_04_10_230056_add_code_to_bot_investments_table.php',
        'database/migrations/2026_09_09_115504_add_referral_eligible_amount_to_bot_investments.php',
        'database/migrations/2026_03_29_235620_create_user_ranks_table.php',
    ]]);

    $this->bot = Bot::forceCreate([
        'name' => 'Alpha', 'slug' => 'alpha', 'min_amount' => 100, 'max_amount' => 100000, 'lock_days' => 30,
        'license_duration_days' => 30, 'daily_return_percent' => 1, 'payout_interval_hours' => 24, 'price' => 50,
    ]);
});

function bonusUser(): User
{
    return User::forceCreate(['name' => 'Test', 'email' => uniqid('u').'@example.com', 'password' => bcrypt('x')]);
}

function giveMatching(User $user, float $amount, int $daysAgo): WalletLedger
{
    $ledger = WalletService::credit($user, $amount, LedgerReference::MATCHINGDEPOSITBONUS, 1, 'matching deposit bonus (100%)', LedgerAsset::DEPOSITBONUSBALANCE);
    $ledger->forceFill(['created_at' => now()->subDays($daysAgo)])->save();

    return $ledger;
}

// Same as investing from deposits: bonus balance first, then the deposit balance
function investFromDeposit(User $user, float $amount, float $fromBonus): BotInvestment
{
    $license = BotLicense::forceCreate(['user_id' => $user->id, 'bot_id' => test()->bot->id, 'starts_at' => now(), 'expires_at' => now()->addDays(30), 'status' => 'active']);

    if ($fromBonus > 0) {
        WalletService::debit($user, $fromBonus, LedgerReference::BOT_INVESTMENT, null, 'investment debit (bonus balance)', LedgerAsset::DEPOSITBONUSBALANCE);
    }
    WalletService::credit($user, $amount, LedgerReference::BOT_INVESTMENT, null, 'locked investment', LedgerAsset::LOCKEDBALANCE);

    return BotInvestment::create([
        'user_id' => $user->id, 'bot_id' => test()->bot->id, 'bot_license_id' => $license->id,
        'amount' => $amount, 'capital' => $amount, 'referral_eligible_amount' => $amount - $fromBonus,
        'started_at' => now(), 'matures_at' => now()->addDays(30), 'next_cycle_at' => now()->addDay(),
    ]);
}

function bal(User $user, LedgerAsset $asset): float
{
    return (float) WalletService::getBalance($user, $asset->value);
}

it('takes the bonus out of a running investment and leaves the real deposit trading', function () {
    $user = bonusUser();
    giveMatching($user, 1000, 40);
    $investment = investFromDeposit($user, 2000, 1000);

    $rows = app(MatchingBonusCleanupService::class)->run(apply: true);

    expect($rows)->toHaveCount(1)
        ->and($rows[0]['from_investments'])->toEqual(1000.0);
    $investment->refresh();
    expect((float) $investment->capital)->toEqual(1000.0)
        ->and((float) $investment->amount)->toEqual(1000.0)
        ->and(bal($user, LedgerAsset::LOCKEDBALANCE))->toEqual(1000.0);
});

it('takes unspent bonus from the bonus balance first', function () {
    $user = bonusUser();
    giveMatching($user, 1000, 35);
    $investment = investFromDeposit($user, 1500, 600);

    $rows = app(MatchingBonusCleanupService::class)->run(apply: true);

    expect($rows[0]['from_balance'])->toEqual(400.0)
        ->and($rows[0]['from_investments'])->toEqual(600.0)
        ->and(bal($user, LedgerAsset::DEPOSITBONUSBALANCE))->toEqual(0.0)
        ->and((float) $investment->fresh()->capital)->toEqual(900.0);
});

it('leaves bonuses younger than 30 days alone', function () {
    $user = bonusUser();
    giveMatching($user, 500, 10);

    expect(app(MatchingBonusCleanupService::class)->run(apply: true))->toBeEmpty()
        ->and(bal($user, LedgerAsset::DEPOSITBONUSBALANCE))->toEqual(500.0);
});

it('never touches investments funded with real money only', function () {
    $user = bonusUser();
    giveMatching($user, 300, 40);
    WalletService::debit($user, 300, LedgerReference::BOT_INVESTMENT, null, 'spent elsewhere', LedgerAsset::DEPOSITBONUSBALANCE);
    $real = investFromDeposit($user, 5000, 0);

    $rows = app(MatchingBonusCleanupService::class)->run(apply: true);

    expect($rows[0]['unrecoverable'])->toEqual(300.0)
        ->and((float) $real->fresh()->capital)->toEqual(5000.0);
});

it('does nothing to finished investments and keeps profit', function () {
    $user = bonusUser();
    giveMatching($user, 1000, 40);
    $investment = investFromDeposit($user, 2000, 1000);
    $investment->update(['status' => BotInvestmentStatus::COMPLETED, 'total_profit' => 250]);

    $rows = app(MatchingBonusCleanupService::class)->run(apply: true);

    $investment->refresh();
    expect($rows[0]['unrecoverable'])->toEqual(1000.0)
        ->and((float) $investment->capital)->toEqual(2000.0)
        ->and((float) $investment->total_profit)->toEqual(250.0);
});

it('preview changes nothing and running twice only removes once', function () {
    $user = bonusUser();
    giveMatching($user, 1000, 40);
    $investment = investFromDeposit($user, 2000, 1000);
    $service = app(MatchingBonusCleanupService::class);

    $preview = $service->run();
    expect($preview[0]['from_investments'])->toEqual(1000.0)
        ->and((float) $investment->fresh()->capital)->toEqual(2000.0);

    $service->run(apply: true);
    expect($service->run(apply: true))->toBeEmpty()
        ->and((float) $investment->fresh()->capital)->toEqual(1000.0)
        ->and(bal($user, LedgerAsset::LOCKEDBALANCE))->toEqual(1000.0);
});

it('runs from the admin users page', function () {
    $user = bonusUser();
    giveMatching($user, 1000, 40);
    $investment = investFromDeposit($user, 2000, 1000);

    \Filament\Facades\Filament::setCurrentPanel('admin');
    \Spatie\Permission\Models\Role::findOrCreate('admin', 'web');
    $admin = bonusUser();
    $admin->assignRole('admin');
    $this->actingAs($admin);

    \Livewire\Livewire::test(\App\Filament\Resources\Users\Pages\ListUsers::class)
        ->assertActionVisible('removeMatchingBonuses')
        ->callAction('removeMatchingBonuses')
        ->assertHasNoActionErrors();

    expect((float) $investment->fresh()->capital)->toEqual(1000.0);
});

it('is silent: the adjustment is not in the user transaction list', function () {
    $user = bonusUser();
    giveMatching($user, 1000, 40);
    investFromDeposit($user, 2000, 1000);
    app(MatchingBonusCleanupService::class)->run(apply: true);

    expect(WalletLedger::where('reference_type', LedgerReference::MATCHING_BONUS_REMOVED)->count())->toBeGreaterThan(0);

    $visible = WalletLedger::where('user_id', $user->id)->visibleToUser()->pluck('reference_type');

    expect($visible->contains(LedgerReference::MATCHING_BONUS_REMOVED))->toBeFalse()
        ->and($visible)->not->toBeEmpty();
});
