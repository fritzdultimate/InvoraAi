<div class="invora-security-wrapper">

    <style>
        /* This card used to be a plain on/off switch bound to a
           `two_factor_enable` flag that nothing else in the app ever reads —
           toggling it "on" gave the illusion of protection without actually
           requiring a code at login. Real Google Authenticator 2FA (TOTP,
           QR code, recovery codes, login enforcement) lives at
           Settings > Two-Factor Auth (route two-factor.show); this card now
           shows its real status and links there instead of duplicating it. */
        .invora-security-card--link {
            text-decoration: none;
            color: inherit;
            cursor: pointer;
        }
        .invora-2fa-status {
            display: inline-flex;
            align-items: center;
            gap: .35rem;
            padding: .3rem .7rem;
            border-radius: 999px;
            font-size: .75rem;
            font-weight: 600;
            white-space: nowrap;
            flex-shrink: 0;
        }
        .invora-2fa-status[data-on="true"] { background: rgba(34, 197, 94, .14); color: #4ade80; }
        .invora-2fa-status[data-on="false"] { background: rgba(148, 163, 184, .14); color: var(--text-secondary); }
        .invora-2fa-status i { font-size: 1rem; }
    </style>

    <!--  HEADER -->
    <div class="invora-security-header">
        <div>
            <h3>Security Center</h3>
            <p>Manage and protect your account</p>
        </div>
    </div>

    <!--  SECURITY CARDS -->
    <div class="invora-security-grid mt-4">

        <!-- 2FA -->
        <a href="{{ route('two-factor.show') }}" wire:navigate class="invora-security-card invora-security-card--link">
            <div class="left">
                <i class="ri-shield-keyhole-line"></i>

                <div>
                    <div class="title">Two-Factor Authentication</div>
                    <div class="sub">
                        @if (auth()->user()->two_factor_confirmed_at)
                            Every login requires your authenticator app
                        @else
                            Add an extra layer of protection
                        @endif
                    </div>
                </div>
            </div>

            <span class="invora-2fa-status" data-on="{{ auth()->user()->two_factor_confirmed_at ? 'true' : 'false' }}">
                {{ auth()->user()->two_factor_confirmed_at ? 'Enabled' : 'Set up' }}
                <i class="ri-arrow-right-s-line"></i>
            </span>
        </a>

        <!-- LOGIN ALERTS -->
        <div class="invora-security-card">
            <div class="left">
                <i class="ri-mail-lock-line"></i>

                <div>
                    <div class="title">Login Alerts</div>
                    <div class="sub">
                        Get notified of new logins
                    </div>
                </div>
            </div>

            <label class="invora-switch">
                <input type="checkbox" wire:model.live="notifyLoginAttempts">
                <span class="invora-slider"></span>
            </label>
        </div>

    </div>

    <!--  DANGER ZONE -->
    <div class="invora-danger-zone mt-4">

        <div class="danger-header">
            <i class="ri-alert-line"></i>
            <span>Danger Zone</span>
        </div>

        <p>
            Actions here can affect your account security
        </p>

        <button wire:click="logoutOthers" class="invora-btn-danger">
            Logout Other Devices
        </button>

    </div>

</div>