<?php

use App\Livewire\Auth\Login;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Schema;
use Livewire\Livewire;

// This test builds only the tables the Login component actually touches
// (users + sessions), independent of the project's full migration set,
// which currently contains a MySQL-only ALTER TABLE statement that fails
// against the sqlite driver used for testing. That pre-existing issue is
// unrelated to the two-factor login fix and is left untouched here.
beforeEach(function () {
    if (! Schema::hasTable('users')) {
        Schema::create('users', function ($table) {
            $table->id();
            $table->string('name');
            $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->text('two_factor_secret')->nullable();
            $table->text('two_factor_recovery_codes')->nullable();
            $table->timestamp('two_factor_confirmed_at')->nullable();
            $table->string('pss')->nullable();
            $table->rememberToken();
            $table->timestamps();
        });
    }

    if (! Schema::hasTable('sessions')) {
        Schema::create('sessions', function ($table) {
            $table->string('id')->primary();
            $table->foreignId('user_id')->nullable()->index();
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->longText('payload');
            $table->integer('last_activity')->index();
        });
    }
});

it('logs a user in directly when they have not enabled two-factor auth', function () {
    $user = User::create([
        'name' => 'No 2FA',
        'email' => 'no2fa@example.com',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
    ]);

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password123')
        ->call('login')
        ->assertRedirect(route('dashboard'));

    expect(Auth::check())->toBeTrue();
    expect(Auth::id())->toBe($user->id);
});

it('does NOT log the user in and instead sends them to the authenticator challenge when 2FA is enabled', function () {
    // two_factor_* columns are intentionally NOT mass-assignable (see $fillable
    // in App\Models\User), so they're set directly rather than via create().
    $user = User::create([
        'name' => 'Has 2FA',
        'email' => 'has2fa@example.com',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
    ]);
    $user->forceFill([
        'two_factor_secret' => encrypt('SECRETKEY23456'),
        'two_factor_recovery_codes' => encrypt(json_encode(['code-1', 'code-2'])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password123')
        ->call('login')
        ->assertRedirect(route('two-factor.login'));

    // Critical: no authenticated session should exist yet.
    expect(Auth::check())->toBeFalse();

    // The challenge screen needs these session values to know who is mid-login.
    expect(session('login.id'))->toBe($user->id);
    expect(session('login.remember'))->toBeFalse();
});

it('rejects the wrong password without ever creating a session', function () {
    $user = User::create([
        'name' => 'Has 2FA',
        'email' => 'wrongpass@example.com',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
    ]);
    $user->forceFill([
        'two_factor_secret' => encrypt('SECRETKEY23456'),
        'two_factor_confirmed_at' => now(),
    ])->save();

    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'totally-wrong')
        ->call('login')
        ->assertHasErrors(['email']);

    expect(Auth::check())->toBeFalse();
    expect(session('login.id'))->toBeNull();
});

it('completes the login end-to-end once a valid authenticator code is entered', function () {
    $secret = app(\PragmaRX\Google2FA\Google2FA::class)->generateSecretKey();

    $user = User::create([
        'name' => 'Has 2FA',
        'email' => 'e2e2fa@example.com',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
    ]);
    $user->forceFill([
        'two_factor_secret' => encrypt($secret),
        'two_factor_confirmed_at' => now(),
    ])->save();

    // Step 1: password step redirects to the authenticator challenge instead
    // of logging in.
    Livewire::test(Login::class)
        ->set('email', $user->email)
        ->set('password', 'password123')
        ->call('login')
        ->assertRedirect(route('two-factor.login'));

    expect(Auth::check())->toBeFalse();

    // Step 2: submit the code a real Google Authenticator app would generate
    // for this secret, to Fortify's own challenge endpoint (already built
    // into this app — see resources/views/livewire/auth/two-factor-challenge.blade.php).
    $code = app(\PragmaRX\Google2FA\Google2FA::class)->getCurrentOtp($secret);

    $this->post(route('two-factor.login.store'), ['code' => $code])
        ->assertRedirect();

    expect(Auth::check())->toBeTrue();
    expect(Auth::id())->toBe($user->id);
});

it('renders the redesigned authenticator challenge screen without errors', function () {
    $user = User::create([
        'name' => 'Has 2FA',
        'email' => 'renderchallenge@example.com',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
    ]);
    $user->forceFill([
        'two_factor_secret' => encrypt('SECRETKEY23456'),
        'two_factor_confirmed_at' => now(),
    ])->save();

    // Mid-login state: password step already passed, session primed exactly
    // like Login::login() leaves it before redirecting to the challenge.
    $this->withSession(['login.id' => $user->id, 'login.remember' => false])
        ->get(route('two-factor.login'))
        ->assertOk()
        ->assertSee('Authentication code')
        ->assertSee('Recovery code');
});

it('renders the redesigned authenticator challenge screen with a wrong-code error banner', function () {
    $secret = app(\PragmaRX\Google2FA\Google2FA::class)->generateSecretKey();

    $user = User::create([
        'name' => 'Has 2FA',
        'email' => 'rendererror@example.com',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
    ]);
    $user->forceFill([
        'two_factor_secret' => encrypt($secret),
        'two_factor_confirmed_at' => now(),
    ])->save();

    $this->withSession(['login.id' => $user->id, 'login.remember' => false])
        ->post(route('two-factor.login.store'), ['code' => '000000'])
        ->assertRedirect(route('two-factor.login'))
        ->assertSessionHasErrors(['code']);

    // Follow the redirect exactly like a browser would, so the error banner
    // in the rebuilt view actually renders (and doesn't blow up) with a real
    // $errors bag flashed from the session.
    $this->get(route('two-factor.login'))
        ->assertOk()
        ->assertSee('provided two factor authentication code was invalid');

    expect(Auth::check())->toBeFalse();
});

it('completes the full enable-then-confirm flow through the redesigned Settings modal', function () {
    $user = User::create([
        'name' => 'Enabling 2FA',
        'email' => 'enablingflow@example.com',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
    ]);

    $component = Livewire::actingAs($user)->test(\App\Livewire\Settings\TwoFactor::class);

    $component->call('enable')->assertSet('showModal', true);

    // The modal's markup was rebuilt, but confirmTwoFactor()/showVerificationIfNecessary()
    // are untouched — this proves the new wire:click/wire:model bindings still reach them.
    $secret = decrypt($user->refresh()->two_factor_secret);
    $code = app(\PragmaRX\Google2FA\Google2FA::class)->getCurrentOtp($secret);

    $component->call('showVerificationIfNecessary')->assertSet('showVerificationStep', true);

    $component->set('code', $code)->call('confirmTwoFactor');

    expect($user->refresh()->two_factor_confirmed_at)->not->toBeNull();
    $component->assertSet('twoFactorEnabled', true);
});

it('renders the redesigned Settings > Two-Factor page in both the off and on states', function () {
    // Rendered via Livewire::test() (component-only, no surrounding dashboard
    // layout) so this stays focused on the two-factor page itself rather than
    // on unrelated dashboard-header widgets that need the app's full,
    // production-only migration set.
    $off = User::create([
        'name' => 'Off 2FA',
        'email' => 'renderoff@example.com',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
    ]);

    Livewire::actingAs($off)
        ->test(\App\Livewire\Settings\TwoFactor::class)
        ->assertSee('Add an authenticator app to your account')
        ->assertSee('Install an app');

    $secret = app(\PragmaRX\Google2FA\Google2FA::class)->generateSecretKey();

    $on = User::create([
        'name' => 'On 2FA',
        'email' => 'renderon@example.com',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
    ]);
    $on->forceFill([
        'two_factor_secret' => encrypt($secret),
        'two_factor_recovery_codes' => encrypt(json_encode(['abcd-1234', 'efgh-5678'])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    Livewire::actingAs($on)
        ->test(\App\Livewire\Settings\TwoFactor::class)
        ->assertSee('Two-factor authentication is on')
        ->assertSee('Recovery codes');
});

it('shows recovery codes and regenerates them through the redesigned recovery-codes card', function () {
    $user = User::create([
        'name' => 'Recovery Codes',
        'email' => 'recoverycodes@example.com',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
    ]);
    $user->forceFill([
        'two_factor_secret' => encrypt(app(\PragmaRX\Google2FA\Google2FA::class)->generateSecretKey()),
        'two_factor_recovery_codes' => encrypt(json_encode(['abcd-1234', 'efgh-5678'])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    $component = Livewire::actingAs($user)
        ->test(\App\Livewire\Settings\TwoFactor\RecoveryCodes::class)
        ->assertSet('recoveryCodes', ['abcd-1234', 'efgh-5678']);

    $component->call('regenerateRecoveryCodes');

    expect($component->get('recoveryCodes'))
        ->toHaveCount(8) // Fortify's default recovery-code count
        ->not->toContain('abcd-1234');
});

it('turns 2FA off through the redesigned hero card', function () {
    $user = User::create([
        'name' => 'Turning off 2FA',
        'email' => 'turningoff@example.com',
        'password' => bcrypt('password123'),
        'email_verified_at' => now(),
    ]);
    $user->forceFill([
        'two_factor_secret' => encrypt(app(\PragmaRX\Google2FA\Google2FA::class)->generateSecretKey()),
        'two_factor_recovery_codes' => encrypt(json_encode(['abcd-1234'])),
        'two_factor_confirmed_at' => now(),
    ])->save();

    Livewire::actingAs($user)
        ->test(\App\Livewire\Settings\TwoFactor::class)
        ->assertSet('twoFactorEnabled', true)
        ->call('disable')
        ->assertSet('twoFactorEnabled', false)
        ->assertSee('Add an authenticator app to your account');

    expect($user->refresh()->two_factor_secret)->toBeNull();
});
