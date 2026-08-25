<div
    class="tfa-recovery-card"
    wire:cloak
    x-data="{
        showRecoveryCodes: false,
        copiedAll: false,
        codes: @js($recoveryCodes),
        async copyAll() {
            try {
                await navigator.clipboard.writeText(this.codes.join('\n'));
                this.copiedAll = true;
                setTimeout(() => this.copiedAll = false, 1500);
            } catch (e) {
                console.warn('Could not copy to clipboard');
            }
        },
        download() {
            const blob = new Blob([this.codes.join('\n') + '\n'], { type: 'text/plain' });
            const url = URL.createObjectURL(blob);
            const a = document.createElement('a');
            a.href = url;
            a.download = 'invoraai-recovery-codes.txt';
            document.body.appendChild(a);
            a.click();
            a.remove();
            URL.revokeObjectURL(url);
        },
    }"
    x-effect="codes = @js($recoveryCodes)"
>
    <style>
        .tfa-recovery-card {
            padding: clamp(1rem, 2.5vw, 1.35rem);
            border-radius: 16px;
            border: 1px solid var(--tfa-border, rgba(255,255,255,.08));
            background: var(--tfa-card, rgb(39, 49, 66));
        }
        .tfa-recovery-head { display: flex; align-items: center; gap: .5rem; }
        .tfa-recovery-actions {
            display: flex;
            flex-wrap: wrap;
            gap: .5rem;
            margin-top: 1rem;
        }
        .tfa-code-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: .5rem;
            margin-top: 1rem;
        }
        @media (min-width: 480px) {
            .tfa-code-grid { grid-template-columns: repeat(3, 1fr); }
        }
        .tfa-code-item {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: .35rem;
            padding: .55rem .65rem;
            border-radius: 10px;
            background: rgba(255,255,255,.03);
            border: 1px solid var(--tfa-border, rgba(255,255,255,.08));
            font-family: ui-monospace, SFMono-Regular, Menlo, monospace;
            font-size: .8125rem;
            color: var(--tfa-text, #e6edf3);
            cursor: pointer;
            transition: border-color .15s ease;
        }
        .tfa-code-item:hover { border-color: var(--tfa-accent, #6366f1); }
        .tfa-code-item .tfa-copy-flag {
            font-size: .625rem;
            color: #4ade80;
            opacity: 0;
            transition: opacity .15s ease;
        }
        .tfa-code-item[data-copied="true"] .tfa-copy-flag { opacity: 1; }

        /* ---- Flux button color fix ----------------------------------------
           See the matching comment in resources/views/livewire/settings/two-factor.blade.php —
           a global, un-layered reset in public/assets/css/style.css strips the
           background/text color off every Flux button in the app. These
           plain (also un-layered) rules win the same way and restyle the
           buttons to match this redesign's accent instead of Flux's default. */
        .tfa-recovery-card [data-flux-button].tfa-btn-primary {
            background: linear-gradient(160deg, var(--tfa-accent, #6366f1), var(--tfa-accent-2, #22d3ee)) !important;
            color: #fff !important;
            border-color: transparent !important;
        }
        .tfa-recovery-card [data-flux-button].tfa-btn-primary:hover { filter: brightness(1.08); }
        .tfa-recovery-card [data-flux-button].tfa-btn-ghost {
            background: transparent !important;
            color: var(--tfa-text-soft, #8b9bb3) !important;
            border-color: transparent !important;
        }
        .tfa-recovery-card [data-flux-button].tfa-btn-ghost:hover {
            background: rgba(255, 255, 255, .05) !important;
            color: var(--tfa-text, #e6edf3) !important;
        }
        .tfa-recovery-card [data-flux-button].tfa-btn-filled {
            background: rgba(255, 255, 255, .06) !important;
            color: var(--tfa-text, #e6edf3) !important;
            border-color: transparent !important;
        }
        .tfa-recovery-card [data-flux-button].tfa-btn-filled:hover { background: rgba(255, 255, 255, .1) !important; }
    </style>

    <div class="tfa-recovery-head">
        <flux:icon.key variant="outline" class="size-4" style="color: var(--tfa-accent, #6366f1)" />
        <flux:heading size="lg" level="3">{{ __('Recovery codes') }}</flux:heading>
    </div>
    <flux:text variant="subtle" class="mt-1.5">
        {{ __('Each code works once. Keep them somewhere safe — a password manager, not your inbox — in case you ever lose your phone.') }}
    </flux:text>

    <div class="tfa-recovery-actions">
        <flux:button
            x-show="!showRecoveryCodes"
            icon="eye"
            icon:variant="outline"
            variant="primary"
            class="tfa-btn-primary"
            @click="showRecoveryCodes = true;"
            aria-expanded="false"
            aria-controls="recovery-codes-section"
        >
            {{ __('View recovery codes') }}
        </flux:button>

        <flux:button
            x-show="showRecoveryCodes"
            icon="eye-slash"
            icon:variant="outline"
            variant="ghost"
            class="tfa-btn-ghost"
            @click="showRecoveryCodes = false"
            aria-expanded="true"
            aria-controls="recovery-codes-section"
        >
            {{ __('Hide') }}
        </flux:button>

        @if (filled($recoveryCodes))
            <flux:button
                x-show="showRecoveryCodes"
                icon="document-duplicate"
                icon:variant="outline"
                variant="ghost"
                class="tfa-btn-ghost"
                @click="copyAll()"
            >
                <span x-show="!copiedAll">{{ __('Copy all') }}</span>
                <span x-show="copiedAll">{{ __('Copied!') }}</span>
            </flux:button>

            <flux:button
                x-show="showRecoveryCodes"
                icon="arrow-down-tray"
                icon:variant="outline"
                variant="ghost"
                class="tfa-btn-ghost"
                @click="download()"
            >
                {{ __('Download .txt') }}
            </flux:button>

            <flux:button
                x-show="showRecoveryCodes"
                icon="arrow-path"
                variant="filled"
                class="tfa-btn-filled"
                wire:click="regenerateRecoveryCodes"
                x-on:click="if (! confirm('{{ __('Regenerating will permanently invalidate your current recovery codes. Continue?') }}')) { $event.stopImmediatePropagation(); }"
            >
                {{ __('Regenerate') }}
            </flux:button>
        @endif
    </div>

    <div
        x-show="showRecoveryCodes"
        x-transition
        id="recovery-codes-section"
        class="relative overflow-hidden"
        x-bind:aria-hidden="!showRecoveryCodes"
    >
        @error('recoveryCodes')
            <flux:callout variant="danger" icon="x-circle" heading="{{ $message }}" class="mt-3" />
        @enderror

        @if (filled($recoveryCodes))
            <div
                class="tfa-code-grid"
                role="list"
                aria-label="Recovery codes"
                wire:loading.class="opacity-50 animate-pulse"
            >
                @foreach($recoveryCodes as $code)
                    <div
                        role="listitem"
                        class="tfa-code-item select-text"
                        x-data="{ copied: false }"
                        x-bind:data-copied="copied"
                        @click="
                            navigator.clipboard.writeText('{{ $code }}').then(() => {
                                copied = true;
                                setTimeout(() => copied = false, 1200);
                            });
                        "
                    >
                        <span>{{ $code }}</span>
                        <span class="tfa-copy-flag">{{ __('copied') }}</span>
                    </div>
                @endforeach
            </div>
            <flux:text variant="subtle" class="text-xs mt-3">
                {{ __('Tap any code to copy it. Used codes are removed automatically — regenerate if you run low.') }}
            </flux:text>
        @endif
    </div>
</div>
