<x-layouts.auth>
    <style>
        /* ==========================================================================
           Two-Factor Authentication — Login challenge
           Matches the green/navy glass look already used by the other Flux-based
           auth screens (see .auth-flux-panel rules in components/layouts/auth.blade.php).
           Scoped to .tfa-auth-scope so nothing else on the page is affected.
           ========================================================================== */
        .tfa-auth-scope {
            --tfa-accent: #00b08b;
            --tfa-accent-glow: rgba(0, 176, 139, .28);
        }

        @keyframes tfa-pulse-ring {
            0% { box-shadow: 0 0 0 0 var(--tfa-accent-glow); }
            70% { box-shadow: 0 0 0 16px rgba(0, 176, 139, 0); }
            100% { box-shadow: 0 0 0 0 rgba(0, 176, 139, 0); }
        }
        @keyframes tfa-shake {
            10%, 90% { transform: translateX(-1px); }
            20%, 80% { transform: translateX(2px); }
            30%, 50%, 70% { transform: translateX(-4px); }
            40%, 60% { transform: translateX(4px); }
        }
        @keyframes tfa-fade-up {
            from { opacity: 0; transform: translateY(8px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .tfa-shake { animation: tfa-shake .5s cubic-bezier(.36,.07,.19,.97) both; }

        .tfa-auth-scope .tfa-icon-ring {
            width: 3.75rem;
            height: 3.75rem;
            border-radius: 999px;
            margin-inline: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            background: linear-gradient(160deg, var(--tfa-accent), #00c99a);
            color: #fff;
            animation: tfa-pulse-ring 2.4s ease-in-out infinite, tfa-fade-up .35s ease both;
        }

        .tfa-auth-scope .tfa-toggle {
            display: inline-flex;
            padding: .2rem;
            border-radius: 999px;
            background: rgba(3, 11, 21, .55);
            border: 1px solid rgba(150, 180, 204, .22);
            width: 100%;
        }
        .tfa-auth-scope .tfa-toggle button {
            flex: 1;
            font-size: .75rem;
            font-weight: 600;
            padding: .5rem .75rem;
            border-radius: 999px;
            color: #8fa3b5;
            transition: all .2s ease;
            cursor: pointer;
        }
        .tfa-auth-scope .tfa-toggle button[data-active="true"] {
            background: linear-gradient(160deg, var(--tfa-accent), #00c99a);
            color: #fff;
        }

        .tfa-auth-scope .tfa-otp-wrap :where([data-flux-otp-input]) {
            width: 2.85rem !important;
            height: 3.25rem !important;
            font-size: 1.25rem !important;
            font-weight: 700;
            border-radius: 10px !important;
            background: rgba(3, 11, 21, .55) !important;
            border-color: rgba(150, 180, 204, .28) !important;
            color: #e6edf3 !important;
        }
        @media (min-width: 400px) {
            .tfa-auth-scope .tfa-otp-wrap :where([data-flux-otp-input]) { width: 3.1rem !important; }
        }
        .tfa-auth-scope .tfa-otp-wrap :where([data-flux-otp-input]:focus) {
            border-color: var(--tfa-accent) !important;
            box-shadow: 0 0 0 3px var(--tfa-accent-glow) !important;
        }
        .tfa-auth-scope .tfa-otp-wrap :where([data-flux-otp]) { justify-content: center; gap: .5rem !important; }

        .tfa-auth-scope .tfa-recovery-input {
            width: 100%;
            text-align: center;
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            letter-spacing: .15em;
            text-transform: uppercase;
            font-size: 1rem;
            padding: .9rem 1rem;
            border-radius: 10px;
            background: rgba(3, 11, 21, .55);
            border: 1px solid rgba(150, 180, 204, .28);
            color: #e6edf3;
            outline: none;
        }
        .tfa-auth-scope .tfa-recovery-input::placeholder { color: #6d8194; text-transform: none; letter-spacing: normal; }
        .tfa-auth-scope .tfa-recovery-input:focus {
            border-color: var(--tfa-accent);
            box-shadow: 0 0 0 3px var(--tfa-accent-glow);
        }

        .tfa-auth-scope .tfa-helper {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: .4rem;
            font-size: .8125rem;
            color: #8fa3b5;
            text-align: center;
        }

        .tfa-auth-scope .tfa-submit-spinner {
            display: inline-block;
            width: 1rem;
            height: 1rem;
            border-radius: 999px;
            border: 2px solid rgba(255,255,255,.35);
            border-top-color: #fff;
            animation: tfa-spin .7s linear infinite;
        }
        @keyframes tfa-spin { to { transform: rotate(360deg); } }

        /* ---- Flux button color fix ----------------------------------------
           See the matching comment in resources/views/livewire/settings/two-factor.blade.php —
           a global, un-layered reset in public/assets/css/style.css strips the
           background/text color off every Flux button in the app (including
           this page's "Continue" submit button, which was rendering as a
           blank box). This plain (also un-layered) rule wins the same way. */
        .tfa-auth-scope [data-flux-button].tfa-btn-primary {
            background: linear-gradient(160deg, var(--tfa-accent), #00c99a) !important;
            color: #fff !important;
            border-color: transparent !important;
        }
        .tfa-auth-scope [data-flux-button].tfa-btn-primary:hover { filter: brightness(1.08); }
        .tfa-auth-scope [data-flux-button].tfa-btn-primary:disabled { filter: grayscale(.3) brightness(.85); }
    </style>

    <div
        class="auth-flux-panel tfa-auth-scope mx-auto flex w-full max-w-md flex-col gap-6"
        x-cloak
        x-data="{
            showRecoveryInput: @js($errors->has('recovery_code')),
            code: '',
            recovery_code: '',
            submitting: false,
            toggleInput() {
                this.showRecoveryInput = !this.showRecoveryInput;

                this.code = '';
                this.recovery_code = '';

                $dispatch('clear-2fa-auth-code');

                $nextTick(() => {
                    this.showRecoveryInput
                        ? this.$refs.recovery_code?.focus()
                        : $dispatch('focus-2fa-auth-code');
                });
            },
        }"
        x-init="
            if (@js($errors->any())) {
                $refs.form.classList.add('tfa-shake');
                setTimeout(() => $refs.form.classList.remove('tfa-shake'), 500);
            }
        "
        x-effect="if (code.length === 6 && !showRecoveryInput && !submitting) { submitting = true; $nextTick(() => $refs.form.requestSubmit()); }"
    >
        <div class="tfa-icon-ring">
            <flux:icon.shield-check variant="solid" class="size-7" />
        </div>

        <div x-show="!showRecoveryInput">
            <x-auth-header
                :title="__('Authentication code')"
                :description="__('Open your authenticator app and enter the 6-digit code to finish signing in.')"
            />
        </div>

        <div x-show="showRecoveryInput">
            <x-auth-header
                :title="__('Recovery code')"
                :description="__('Lost access to your authenticator app? Enter one of your saved recovery codes instead.')"
            />
        </div>

        <form method="POST" action="{{ route('two-factor.login.store') }}" x-ref="form" @submit="submitting = true">
            @csrf

            <div class="space-y-5">
                <div class="tfa-toggle">
                    <button type="button" @click="if (showRecoveryInput) toggleInput()" x-bind:data-active="!showRecoveryInput">
                        {{ __('Authenticator code') }}
                    </button>
                    <button type="button" @click="if (! showRecoveryInput) toggleInput()" x-bind:data-active="showRecoveryInput">
                        {{ __('Recovery code') }}
                    </button>
                </div>

                <div x-show="!showRecoveryInput">
                    <div class="flex items-center justify-center my-2 tfa-otp-wrap">
                        <flux:otp
                            x-model="code"
                            length="6"
                            name="code"
                            label="OTP Code"
                            label:sr-only
                            class="mx-auto"
                         />
                    </div>

                    <div class="tfa-helper mt-3">
                        <flux:icon.device-phone-mobile variant="outline" class="size-4" />
                        {{ __('Codes refresh every 30 seconds') }}
                    </div>

                    @error('code')
                        <flux:text color="red" class="text-center mt-2">{{ $message }}</flux:text>
                    @enderror
                </div>

                <div x-show="showRecoveryInput">
                    <input
                        type="text"
                        name="recovery_code"
                        x-ref="recovery_code"
                        x-bind:required="showRecoveryInput"
                        autocomplete="one-time-code"
                        x-model="recovery_code"
                        placeholder="{{ __('xxxxx-xxxxx') }}"
                        class="tfa-recovery-input"
                    />

                    @error('recovery_code')
                        <flux:text color="red" class="text-center mt-2">{{ $message }}</flux:text>
                    @enderror
                </div>

                <flux:button
                    variant="primary"
                    type="submit"
                    :loading="false"
                    class="w-full tfa-btn-primary"
                    x-bind:disabled="submitting || (!showRecoveryInput && code.length < 6) || (showRecoveryInput && recovery_code.length === 0)"
                >
                    <span x-show="!submitting">{{ __('Continue') }}</span>
                    <span x-show="submitting" class="inline-flex items-center gap-2">
                        <span class="tfa-submit-spinner"></span>
                        {{ __('Verifying…') }}
                    </span>
                </flux:button>
            </div>

            <div class="mt-5 text-sm leading-5 text-center text-[#8fa3b5]">
                <flux:link :href="route('login')" wire:navigate variant="subtle" :accent="false" class="auth-flux-footer-link font-medium">
                    {{ __('← Back to login') }}
                </flux:link>
            </div>
        </form>
    </div>
</x-layouts.auth>
