<section class="w-full">
    @include('partials.settings-heading')

    <style>
        /* ==========================================================================
           Two-Factor Authentication — Settings page
           Scoped to .tfa-scope / .tfa-modal so nothing else in the app is touched.
           Reuses the dashboard's own design tokens (--accent / --bg-card / --border)
           from resources/css/invora-ui.css so this feels native to the rest of the
           product instead of bolted on.
           ========================================================================== */
        .tfa-scope {
            --tfa-accent: var(--accent, #6366f1);
            --tfa-accent-glow: var(--accent-glow, rgba(99, 102, 241, .25));
            --tfa-accent-2: #22d3ee;
            --tfa-card: var(--bg-card, rgb(39, 49, 66));
            --tfa-border: var(--border, rgba(255, 255, 255, .08));
            --tfa-text: var(--text-primary, #e6edf3);
            --tfa-text-soft: var(--text-secondary, #8b9bb3);
            width: 100%;
        }

        @keyframes tfa-pulse-ring {
            0% { box-shadow: 0 0 0 0 var(--tfa-accent-glow); }
            70% { box-shadow: 0 0 0 14px rgba(99, 102, 241, 0); }
            100% { box-shadow: 0 0 0 0 rgba(99, 102, 241, 0); }
        }

        @keyframes tfa-fade-up {
            from { opacity: 0; transform: translateY(6px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .tfa-hero {
            position: relative;
            display: flex;
            flex-direction: column;
            gap: 1.1rem;
            padding: clamp(1.1rem, 2.5vw, 1.6rem);
            border-radius: 18px;
            background: linear-gradient(145deg, rgba(99, 102, 241, .10), rgba(34, 211, 238, .05) 60%, transparent);
            border: 1px solid var(--tfa-border);
            overflow: hidden;
            animation: tfa-fade-up .35s ease both;
        }
        .tfa-hero::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(420px 160px at 90% -10%, var(--tfa-accent-glow), transparent 70%);
            pointer-events: none;
        }
        @media (min-width: 640px) {
            .tfa-hero { flex-direction: row; align-items: center; }
        }

        .tfa-hero-icon-wrap {
            position: relative;
            flex-shrink: 0;
            display: flex;
            align-items: center;
            justify-content: center;
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 999px;
            background: linear-gradient(160deg, var(--tfa-accent), var(--tfa-accent-2));
            color: #fff;
        }
        .tfa-hero-icon-wrap[data-on="true"] { animation: tfa-pulse-ring 2.4s ease-in-out infinite; }
        .tfa-hero-icon-wrap[data-on="false"] { background: linear-gradient(160deg, #6b7280, #4b5563); }

        .tfa-pill {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .2rem .6rem;
            border-radius: 999px;
            font-size: .6875rem;
            font-weight: 600;
            letter-spacing: .04em;
            text-transform: uppercase;
            width: fit-content;
        }
        .tfa-pill[data-on="true"] { background: rgba(34, 197, 94, .14); color: #4ade80; }
        .tfa-pill[data-on="false"] { background: rgba(148, 163, 184, .14); color: var(--tfa-text-soft); }
        .tfa-pill-dot { width: .4rem; height: .4rem; border-radius: 999px; background: currentColor; }

        .tfa-hero-title { font-size: 1.05rem; font-weight: 600; color: var(--tfa-text); margin-top: .35rem; }
        .tfa-hero-desc { font-size: .875rem; line-height: 1.5; color: var(--tfa-text-soft); margin-top: .3rem; max-width: 46ch; }
        .tfa-hero-meta { display: flex; align-items: center; gap: .35rem; font-size: .75rem; color: var(--tfa-text-soft); margin-top: .55rem; }

        .tfa-hero-cta { flex-shrink: 0; width: 100%; }
        @media (min-width: 640px) { .tfa-hero-cta { width: auto; } }

        .tfa-steps {
            /* x-settings.layout caps its slot at max-w-lg (512px) regardless of
               viewport, so a viewport media query forcing 3 fixed columns here
               squeezed the card text no matter how wide the screen was.
               auto-fit + minmax sizes off the ACTUAL available width instead,
               with no query needed: 1 column on mobile, 2-3 once there's
               genuinely enough room. */
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: .75rem;
            margin-top: 1.25rem;
        }

        .tfa-step-card {
            display: flex;
            gap: .75rem;
            align-items: flex-start;
            padding: 1rem;
            border-radius: 14px;
            border: 1px solid var(--tfa-border);
            background: var(--tfa-card);
        }
        .tfa-step-num {
            flex-shrink: 0;
            width: 1.75rem;
            height: 1.75rem;
            border-radius: 999px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: .75rem;
            font-weight: 700;
            color: #fff;
            background: linear-gradient(160deg, var(--tfa-accent), var(--tfa-accent-2));
        }
        .tfa-step-card-title { font-size: .8125rem; font-weight: 600; color: var(--tfa-text); }
        .tfa-step-card-desc { font-size: .75rem; color: var(--tfa-text-soft); margin-top: .15rem; line-height: 1.45; }

        .tfa-info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: .75rem;
            margin-top: 1.25rem;
        }
        .tfa-info-card {
            padding: .9rem 1rem;
            border-radius: 14px;
            border: 1px solid var(--tfa-border);
            background: var(--tfa-card);
            display: flex;
            gap: .65rem;
            align-items: flex-start;
        }
        .tfa-info-card-title { font-size: .75rem; font-weight: 600; color: var(--tfa-text); }
        .tfa-info-card-desc { font-size: .6875rem; color: var(--tfa-text-soft); margin-top: .1rem; line-height: 1.4; }
        .tfa-info-icon { color: var(--tfa-accent); flex-shrink: 0; }

        /* ---- Modal ---- */
        .tfa-modal { display: flex; flex-direction: column; gap: 1.5rem; }

        .tfa-modal-icon-ring {
            width: 4.5rem;
            height: 4.5rem;
            border-radius: 999px;
            margin-inline: auto;
            display: flex;
            align-items: center;
            justify-content: center;
            position: relative;
            background: radial-gradient(circle at 30% 20%, rgba(99,102,241,.35), rgba(34,211,238,.12) 60%, transparent 75%);
        }
        .tfa-modal-icon-ring::before {
            content: "";
            position: absolute;
            inset: 0;
            border-radius: 999px;
            border: 1px dashed var(--tfa-accent);
            opacity: .45;
            animation: tfa-spin 12s linear infinite;
        }
        @keyframes tfa-spin { to { transform: rotate(360deg); } }

        .tfa-stepper { display: flex; align-items: center; justify-content: center; gap: .5rem; }
        .tfa-stepper-dot {
            display: flex; align-items: center; gap: .4rem;
            font-size: .6875rem; font-weight: 600; color: var(--tfa-text-soft);
        }
        .tfa-stepper-dot .tfa-dot-circle {
            width: 1.35rem; height: 1.35rem; border-radius: 999px;
            display: flex; align-items: center; justify-content: center;
            font-size: .6875rem; border: 1px solid var(--tfa-border);
            color: var(--tfa-text-soft); transition: all .25s ease;
        }
        .tfa-stepper-dot[data-active="true"] .tfa-dot-circle {
            background: linear-gradient(160deg, var(--tfa-accent), var(--tfa-accent-2));
            border-color: transparent; color: #fff;
        }
        .tfa-stepper-dot[data-active="true"] { color: var(--tfa-text); }
        .tfa-stepper-line { width: 2rem; height: 1px; background: var(--tfa-border); }

        .tfa-toggle {
            display: inline-flex;
            padding: .2rem;
            border-radius: 999px;
            background: var(--tfa-card);
            border: 1px solid var(--tfa-border);
            width: 100%;
        }
        .tfa-toggle button {
            flex: 1;
            font-size: .75rem;
            font-weight: 600;
            padding: .45rem .75rem;
            border-radius: 999px;
            color: var(--tfa-text-soft);
            transition: all .2s ease;
            cursor: pointer;
        }
        .tfa-toggle button[data-active="true"] {
            background: linear-gradient(160deg, var(--tfa-accent), var(--tfa-accent-2));
            color: #fff;
        }

        .tfa-qr-frame {
            position: relative;
            width: 12rem;
            height: 12rem;
            margin-inline: auto;
            border-radius: 18px;
            padding: .65rem;
            background: #fff;
        }
        .tfa-qr-corner {
            position: absolute; width: 1.35rem; height: 1.35rem;
            border: 2px solid var(--tfa-accent);
        }
        .tfa-qr-corner.tl { top: -6px; left: -6px; border-right: none; border-bottom: none; border-radius: 8px 0 0 0; }
        .tfa-qr-corner.tr { top: -6px; right: -6px; border-left: none; border-bottom: none; border-radius: 0 8px 0 0; }
        .tfa-qr-corner.bl { bottom: -6px; left: -6px; border-right: none; border-top: none; border-radius: 0 0 0 8px; }
        .tfa-qr-corner.br { bottom: -6px; right: -6px; border-left: none; border-top: none; border-radius: 0 0 8px 0; }

        .tfa-key-chip {
            display: flex;
            align-items: stretch;
            border-radius: 12px;
            border: 1px solid var(--tfa-border);
            overflow: hidden;
            background: var(--tfa-card);
        }
        .tfa-key-chip input {
            flex: 1;
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            letter-spacing: .12em;
            font-size: .875rem;
            padding: .75rem .9rem;
            background: transparent;
            border: none;
            outline: none;
            color: var(--tfa-text);
            min-width: 0;
        }
        .tfa-key-chip button {
            padding: 0 .9rem;
            border-left: 1px solid var(--tfa-border);
            color: var(--tfa-text-soft);
            cursor: pointer;
            flex-shrink: 0;
        }
        .tfa-key-chip button:hover { color: var(--tfa-accent); }

        .tfa-otp-wrap :where([data-flux-otp-input]) {
            width: 2.75rem !important;
            height: 3rem !important;
            font-size: 1.15rem !important;
            font-weight: 600;
            border-radius: 10px !important;
        }
        @media (min-width: 400px) {
            .tfa-otp-wrap :where([data-flux-otp-input]) { width: 3rem !important; }
        }
        .tfa-otp-wrap :where([data-flux-otp]) { justify-content: center; gap: .45rem !important; }

        .tfa-helper-row {
            display: flex;
            align-items: center;
            gap: .5rem;
            justify-content: center;
            font-size: .75rem;
            color: var(--tfa-text-soft);
            text-align: center;
        }

        /* ---- Flux button color fix ----------------------------------------
           public/assets/css/style.css ships a global, un-layered reset
           ( button, [type="button"], [type="reset"], [type="submit"] {
             background-color: transparent } plus color: inherit on the same
           elements). Because it isn't wrapped in a CSS @layer, it beats
           Flux's own button classes even though those come later in the
           document — unlayered rules always win over layered ones
           (Tailwind's utilities live in @layer utilities), regardless of
           source order or specificity. The practical effect: every Flux
           button in this app renders with an invisible background and
           inherited (often invisible) text. These rules are themselves
           plain/un-layered so they win the same way, and double as the
           accent styling for this redesign instead of Flux's plain default. */
        .tfa-scope [data-flux-button].tfa-btn-primary {
            background: linear-gradient(160deg, var(--tfa-accent), var(--tfa-accent-2)) !important;
            color: #fff !important;
            border-color: transparent !important;
        }
        .tfa-scope [data-flux-button].tfa-btn-primary:hover {
            filter: brightness(1.08);
        }
        .tfa-scope [data-flux-button].tfa-btn-danger {
            background: #ef4444 !important;
            color: #fff !important;
            border-color: transparent !important;
        }
        .tfa-scope [data-flux-button].tfa-btn-danger:hover { background: #dc2626 !important; }
        .tfa-scope [data-flux-button].tfa-btn-outline {
            background: var(--tfa-card) !important;
            color: var(--tfa-text) !important;
            border-color: var(--tfa-border) !important;
        }
        .tfa-scope [data-flux-button].tfa-btn-outline:hover { border-color: var(--tfa-accent) !important; }

        /* The enable-2FA modal's own dialog element (rendered by the Flux
           modal component, OUTSIDE this file's .tfa-scope wrapper —
           .tfa-scope only wraps the slot content INSIDE the dialog, so none
           of the .tfa-scope-scoped rules above can ever reach the dialog
           itself) was showing as a plain white box instead of the app's dark
           theme. Flux gives the dialog "bg-white" and "dark:bg-zinc-800",
           both real (layered) Tailwind utility classes that DO compile and
           DO include the dark variant — but public/assets/css/style.css
           ships its own leftover, un-layered "bg-white" background rule
           (confirmed via a live browser check of which rule actually wins).
           Per the CSS cascade-layers spec, an un-layered rule always beats a
           layered one no matter the source order or the dark-mode variant,
           so that legacy rule was silently painting the dialog white — the
           exact same class of bug already fixed above for this page's
           buttons. Fixed the same way: a plain, un-layered, !important rule
           targeting the dialog via a marker class added to the Flux modal
           component's own class attribute below. */
        dialog.tfa-dialog {
            background: var(--bg-card, rgb(39, 49, 66)) !important;
            border-color: var(--border, rgba(255, 255, 255, .08)) !important;
            color: var(--text-primary, #e6edf3) !important;
            box-shadow: 0 24px 70px -12px rgba(0, 0, 0, .55) !important;
        }
    </style>

    <x-settings.layout
        :heading="__('Two Factor Authentication')"
        :subheading="__('Verify every login with your authenticator app, the same way apps like Google and your bank do it')"
    >
        <div class="tfa-scope" wire:cloak style="">
            <div class="tfa-hero">
                <div class="tfa-hero-icon-wrap" data-on="{{ $twoFactorEnabled ? 'true' : 'false' }}">
                    @if ($twoFactorEnabled)
                        <flux:icon.shield-check variant="solid" class="size-6" />
                    @else
                        <flux:icon.shield-exclamation variant="solid" class="size-6" />
                    @endif
                </div>

                <div class="flex-1 min-w-0">
                    <span class="tfa-pill" data-on="{{ $twoFactorEnabled ? 'true' : 'false' }}">
                        <span class="tfa-pill-dot"></span>
                        {{ $twoFactorEnabled ? __('Protected') : __('Not protected') }}
                    </span>

                    <div class="tfa-hero-title">
                        {{ $twoFactorEnabled ? __('Two-factor authentication is on') : __('Add an authenticator app to your account') }}
                    </div>

                    <div class="tfa-hero-desc">
                        @if ($twoFactorEnabled)
                            {{ __('Every login now requires the 6-digit code from your authenticator app, on top of your password.') }}
                        @else
                            {{ __('Once enabled, a password alone will no longer be enough to sign in — every login also asks for the 6-digit code from your phone.') }}
                        @endif
                    </div>

                    @if ($twoFactorEnabled && auth()->user()->two_factor_confirmed_at)
                        <div class="tfa-hero-meta">
                            <flux:icon.clock variant="outline" class="size-3.5" />
                            {{ __('Enabled on :date', ['date' => auth()->user()->two_factor_confirmed_at->format('M j, Y \a\t g:ia')]) }}
                        </div>
                    @endif
                </div>

                <div class="tfa-hero-cta">
                    @if ($twoFactorEnabled)
                        <flux:button
                            variant="danger"
                            icon="shield-exclamation"
                            icon:variant="outline"
                            wire:click="disable"
                            wire:loading.attr="disabled"
                            wire:target="disable"
                            class="w-full sm:w-auto tfa-btn-danger px-4"
                        >
                            <span wire:loading.remove wire:target="disable">{{ __('Turn off') }}</span>
                            <span wire:loading wire:target="disable">{{ __('Turning off…') }}</span>
                        </flux:button>
                    @else
                        <flux:button
                            variant="primary"
                            icon="shield-check"
                            icon:variant="outline"
                            wire:click="enable"
                            wire:loading.attr="disabled"
                            wire:target="enable"
                            class="w-full sm:w-auto tfa-btn-primary px-4"
                        >
                            <span wire:loading.remove wire:target="enable">{{ __('Enable 2FA') }}</span>
                            <span wire:loading wire:target="enable">{{ __('Preparing…') }}</span>
                        </flux:button>
                    @endif
                </div>
            </div>

            @if ($twoFactorEnabled)
                <div class="tfa-info-grid" class="mb-5">
                    <div class="tfa-info-card">
                        <flux:icon.device-phone-mobile variant="outline" class="size-5 tfa-info-icon" />
                        <div>
                            <div class="tfa-info-card-title">{{ __('Authenticator app') }}</div>
                            <div class="tfa-info-card-desc">{{ __('Google Authenticator, Authy, 1Password or any TOTP app you already scanned the QR code with.') }}</div>
                        </div>
                    </div>
                    <div class="tfa-info-card">
                        <flux:icon.key variant="outline" class="size-5 tfa-info-icon" />
                        <div>
                            <div class="tfa-info-card-title">{{ __('Recovery codes') }}</div>
                            <div class="tfa-info-card-desc">{{ __('One-time backup codes below — use one if you ever lose access to your authenticator app.') }}</div>
                        </div>
                    </div>
                    <div class="tfa-info-card">
                        <flux:icon.lock-closed variant="outline" class="size-5 tfa-info-icon" />
                        <div>
                            <div class="tfa-info-card-title">{{ __('Every login') }}</div>
                            <div class="tfa-info-card-desc">{{ __('Your password alone can no longer sign you in — the code is required every time.') }}</div>
                        </div>
                    </div>
                </div>

                <livewire:settings.two-factor.recovery-codes :$requiresConfirmation />
            @else
                <div class="tfa-steps">
                    <div class="tfa-step-card">
                        <div class="tfa-step-num">1</div>
                        <div>
                            <div class="tfa-step-card-title">{{ __('Install an app') }}</div>
                            <div class="tfa-step-card-desc">{{ __('Get Google Authenticator (or Authy / 1Password) from your phone\'s app store — it\'s free.') }}</div>
                        </div>
                    </div>
                    <div class="tfa-step-card">
                        <div class="tfa-step-num">2</div>
                        <div>
                            <div class="tfa-step-card-title">{{ __('Scan the QR code') }}</div>
                            <div class="tfa-step-card-desc">{{ __('Click "Enable 2FA" and point your camera at the code, or enter the setup key by hand.') }}</div>
                        </div>
                    </div>
                    <div class="tfa-step-card">
                        <div class="tfa-step-num">3</div>
                        <div>
                            <div class="tfa-step-card-title">{{ __('Confirm & save codes') }}</div>
                            <div class="tfa-step-card-desc">{{ __('Enter the 6-digit code shown to confirm, then save your recovery codes somewhere safe.') }}</div>
                        </div>
                    </div>
                </div>
            @endif
        </div>
    </x-settings.layout>

    <flux:modal
        name="two-factor-setup-modal"
        class="max-w-md md:min-w-md tfa-dialog"
        @close="closeModal"
        wire:model="showModal"
    >
        <div class="tfa-modal tfa-scope">
            @if ($requiresConfirmation && ! $twoFactorEnabled)
                <div class="tfa-stepper">
                    <div class="tfa-stepper-dot" data-active="{{ $showVerificationStep ? 'false' : 'true' }}">
                        <span class="tfa-dot-circle">
                            @if ($showVerificationStep)
                                <flux:icon.check class="size-3" />
                            @else
                                1
                            @endif
                        </span>
                        {{ __('Scan') }}
                    </div>
                    <div class="tfa-stepper-line"></div>
                    <div class="tfa-stepper-dot" data-active="{{ $showVerificationStep ? 'true' : 'false' }}">
                        <span class="tfa-dot-circle">2</span>
                        {{ __('Verify') }}
                    </div>
                </div>
            @endif

            <div class="flex flex-col items-center space-y-4">
                <div class="tfa-modal-icon-ring">
                    <flux:icon.qr-code class="relative z-10 size-6" style="color: var(--tfa-accent)" />
                </div>

                <div class="space-y-1.5 text-center">
                    <flux:heading size="lg">{{ $this->modalConfig['title'] }}</flux:heading>
                    <flux:text>{{ $this->modalConfig['description'] }}</flux:text>
                </div>
            </div>

            @if ($showVerificationStep)
                <div class="space-y-6">
                    <div class="flex flex-col items-center justify-center space-y-3 tfa-otp-wrap">
                        <flux:otp
                            name="code"
                            wire:model="code"
                            length="6"
                            label="OTP Code"
                            label:sr-only
                            class="mx-auto"
                        />
                    </div>

                    <div class="tfa-helper-row">
                        <flux:icon.device-phone-mobile variant="outline" class="size-4" />
                        {{ __('Enter the current code from your authenticator app') }}
                    </div>

                    @error('code')
                        <flux:callout variant="danger" icon="x-circle" heading="{{ $message }}" />
                    @enderror

                    <div class="flex items-center space-x-3">
                        <flux:button
                            variant="outline"
                            class="flex-1 tfa-btn-outline mr-3"
                            wire:click="resetVerification"
                        >
                            {{ __('Back') }}
                        </flux:button>

                        <flux:button
                            variant="primary"
                            class="flex-1 tfa-btn-primary"
                            wire:click="confirmTwoFactor"
                            wire:loading.attr="disabled"
                            wire:target="confirmTwoFactor"
                            x-bind:disabled="$wire.code.length < 6"
                        >
                            <span wire:loading.remove wire:target="confirmTwoFactor">{{ __('Confirm') }}</span>
                            <span wire:loading wire:target="confirmTwoFactor">{{ __('Verifying…') }}</span>
                        </flux:button>
                    </div>
                </div>
            @else
                <div class="space-y-5" x-data="{ mode: 'qr' }">
                    @error('setupData')
                        <flux:callout variant="danger" icon="x-circle" heading="{{ $message }}" />
                    @enderror

                    <div class="tfa-toggle">
                        <button type="button" x-on:click="mode = 'qr'" x-bind:data-active="mode === 'qr'">
                            {{ __('Scan QR code') }}
                        </button>
                        <button type="button" x-on:click="mode = 'manual'" x-bind:data-active="mode === 'manual'">
                            {{ __('Enter manually') }}
                        </button>
                    </div>

                    <div x-show="mode === 'qr'" x-transition>
                        <div class="tfa-qr-frame">
                            <span class="tfa-qr-corner tl"></span>
                            <span class="tfa-qr-corner tr"></span>
                            <span class="tfa-qr-corner bl"></span>
                            <span class="tfa-qr-corner br"></span>

                            @empty($qrCodeSvg)
                                <div class="flex items-center justify-center w-full h-full">
                                    <flux:icon.loading />
                                </div>
                            @else
                                <div class="flex items-center justify-center w-full h-full">
                                    {!! $qrCodeSvg !!}
                                </div>
                            @endempty
                        </div>
                        <div class="tfa-helper-row" style="margin-top: .85rem;">
                            <flux:icon.device-phone-mobile variant="outline" class="size-4" />
                            {{ __('Open your authenticator app and scan this code') }}
                        </div>
                    </div>

                    <div
                        x-show="mode === 'manual'"
                        x-transition
                        x-data="{
                            copied: false,
                            async copy() {
                                try {
                                    await navigator.clipboard.writeText('{{ $manualSetupKey }}');
                                    this.copied = true;
                                    setTimeout(() => this.copied = false, 1500);
                                } catch (e) {
                                    console.warn('Could not copy to clipboard');
                                }
                            }
                        }"
                    >
                        <div class="tfa-helper-row" style="margin-bottom: .6rem;">
                            {{ __('Add this key as a "time based" account in your authenticator app') }}
                        </div>

                        <div class="tfa-key-chip">
                            @empty($manualSetupKey)
                                <div class="flex items-center justify-center w-full p-3">
                                    <flux:icon.loading variant="mini" />
                                </div>
                            @else
                                <input
                                    type="text"
                                    readonly
                                    value="{{ implode(' ', str_split($manualSetupKey, 4)) }}"
                                    onfocus="this.select()"
                                />

                                <button @click="copy()" type="button">
                                    <flux:icon.document-duplicate x-show="!copied" variant="outline" class="size-4" />
                                    <flux:icon.check x-show="copied" variant="solid" class="text-green-500 size-4" />
                                </button>
                            @endempty
                        </div>
                    </div>
                </div>

                <div>
                    <flux:button
                        :disabled="$errors->has('setupData')"
                        variant="primary"
                        class="w-full tfa-btn-primary"
                        style="display: flex!important;"
                        wire:click="showVerificationIfNecessary"
                    >
                        {{ $this->modalConfig['buttonText'] }} <flux:icon.chevron-right variant="outline" class="size-4" />
                    </flux:button>
                </div>
            @endif
        </div>
    </flux:modal>
</section>
