@push('styles')
    <style>
        .invora-tooltip {
            position: absolute;
            top: 28px;
            left: -700%;
            width: 260px;

            background: #020617;
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 12px;

            padding: 14px;
            z-index: 50;

            box-shadow: 0 15px 40px rgba(0, 0, 0, 0.6);
        }

        .invora-tooltip .title {
            font-size: 13px;
            font-weight: 600;
            color: #fff;
            margin-bottom: 6px;
        }

        .invora-tooltip .text {
            font-size: 12px;
            color: #94a3b8;
            margin-bottom: 8px;
        }

        .invora-tooltip ul {
            font-size: 12px;
            color: #e2e8f0;
            line-height: 1.6;
        }

        .invora-mini-btn {
            display: inline-block;
            margin-top: 10px;
            font-size: 12px;
            color: #22c55e !important;
            font-weight: 600;
        }

        .highlight-box {
            margin-top: 10px;
            padding: 10px 12px;
            border-radius: 10px;

            background: rgba(34,197,94,0.08);
            border: 1px solid rgba(34,197,94,0.2);

            font-size: 12px;
            color: #22c55e;
            line-height: 1.5;
        }
    </style>
@endpush

<div class="invora-profile-wrapper">

    <!-- HEADER -->
    <div class="invora-profile-header">
        <div class="invora-profile-left">
            <div class="invora-avatar">
                {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
            </div>

            <div>
                <div class="invora-profile-name">
                    {{ auth()->user()->name }}
                </div>

                <div class="invora-profile-meta">
                    {{ auth()->user()->email }}
                </div>

                <div
                    class="invora-profile-status {{ auth()->user()->hasActiveLicense() ? 'invora-credit' : 'invora-debit' }} flex items-center gap-2 mt-3">
                    ● {{ auth()->user()->hasActiveLicense() ? 'Account Active' : 'Account Inactive' }}

                    @unless (auth()->user()->hasActiveLicense())
                        <span x-data="{ open: false }" class="relative flex items-center" x-cloak style="position:relative">
                            <iconify-icon icon="solar:info-circle-outline"
                                class="cursor-pointer text-gray-400 hover:text-white transition"
                                @click="open = !open"></iconify-icon>

                            <div x-show="open" @click.outside="open = false" x-transition class="invora-tooltip hidden">
                                <div class="title">Why is my account inactive?</div>

                                <div class="text">
                                    To activate your account:
                                </div>

                                <ul>
                                    <li>• Generate at least <strong>$500</strong> volume within 30 days</li>
                                    <li>• Refer at least <strong>1 active user</strong> who has invested</li>
                                </ul>

                                @php
                                    $refLink = url('/register?ref=' . auth()->user()->affiliate_code);
                                @endphp

                                <div x-data="{ copied: false }">

                                    <button 
                                        @click="
                                            const link = '{{ $refLink }}';

                                            if (navigator.share) {
                                                navigator.share({
                                                    title: 'Join me on Invora',
                                                    text: 'Start investing with me 🚀',
                                                    url: link
                                                });
                                            } else {
                                                navigator.clipboard.writeText(link);
                                                copied = true;
                                                setTimeout(() => copied = false, 2000);
                                            }
                                        "
                                        class="invora-mini-btn"
                                    >
                                        Invite a User →
                                    </button>

                                    <span x-show="copied" x-transition class="text-green-400 text-xs ml-2">
                                        Copied ✅
                                    </span>

                                </div>
                            </div>

                            <div x-show="open" @click.outside="open = false" x-transition class="invora-tooltip">
                                <div class="title">Why is my account inactive?</div>

                                <div class="text">
                                    Your account is currently inactive because you have not activated a trading bot on the Invora Smart Trading platform.
                                </div>

                                <div class="text" style="margin-top:8px;">
                                    To activate your account, you need to purchase and activate a trading bot.
                                </div>

                                <div class="highlight-box">
                                    🚀 Activate your account by getting a bot license and start earning automatically.
                                </div>

                                <!-- CTA -->
                                <a href="{{ route('bot') }}" class="invora-mini-btn">
                                    Get Trading Bot →
                                </a>
                            </div>
                        </span>
                    @endunless
                </div>
            </div>
        </div>

        <div class="invora-kyc-badge {{ auth()->user()->kyc_status }} flex items-center gap-1">
            <iconify-icon icon="solar:medal-star-outline" class="menu-icon"></iconify-icon>
            {{ strtoupper(auth()->user()->rank?->rank?->name ?? 'Unranked') }}
        </div>
    </div>

    <!-- GRID -->
    <div class="invora-profile-grid">

        <!-- LEFT -->
        <div class="invora-profile-main">

            <!-- PERSONAL -->
            <div class="invora-card">
                <div class="invora-card-header">
                    <i class="ri-user-3-line"></i> Personal Info
                </div>

                <div class="invora-form-grid">
                    <div class="invora-input">
                        <label>Full Name</label>
                        <input type="text" wire:model.defer="fullname" disabled>
                    </div>

                    <div class="invora-input">
                        <label>Email</label>
                        <input type="email" value="{{ auth()->user()->email }}" disabled>
                    </div>

                    <div class="invora-input full">
                        <label>Phone</label>
                        <input type="text" wire:model.defer="phone">
                    </div>

                    <div class="invora-input">
                        <label>Country</label>
                        <input type="text" wire:model.defer="country">
                    </div>

                    <div class="invora-input">
                        <label>Date of Birth</label>
                        <input type="date" wire:model.defer="dob">
                    </div>
                </div>

                <button class="invora-btn-primary" wire:click="updateChanges">
                    <span wire:target="updateChanges" wire:loading.remove>Save Changes</span>
                    <span class="spinner" wire:loading wire:target="updateChanges"></span>
                </button>
            </div>

            <!-- SECURITY -->
            <div class="invora-card mt-4">
                <div class="invora-card-header">
                    <i class="ri-shield-keyhole-line"></i> Security
                </div>

                <div class="invora-form-grid">
                    <div class="invora-input">
                        <label>Current Password</label>
                        <input type="password" placeholder="Current Password" wire:model.defer="current_password">

                        @error('current_password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="invora-input">
                        <label>New Password</label>
                        <input type="password" placeholder="New Password" wire:model.defer="password">

                        @error('password')
                            <span class="form-error">{{ $message }}</span>
                        @enderror
                    </div>
                    <div class="invora-input">
                        <label>Confirm Password</label>
                        <input type="password" placeholder="Confirm Password" wire:model.defer="password_confirmation">

                        @error('password_confirmation')
                            <span>{{ $message }}</span>
                        @enderror
                    </div>


                </div>

                <button class="invora-btn-primary" wire:click="updatePassword" wire:loading.attr="disabled">
                    <span wire:loading wire:target="updatePassword">
                        <span class="spinner"></span>
                        Please wait...
                    </span>
                    <span wire:loading.remove wire:target="updatePassword">Update Password</span>
                </button>
            </div>

        </div>

        <!-- RIGHT -->
        <div class="invora-profile-side">

            <!-- STATS -->
            <div class="invora-mini-grid">

                <div class="invora-mini-card">
                    <div class="invora-mini-title">AI Status</div>
                    <div class="invora-mini-value invora-credit">
                        Running
                    </div>
                </div>

                <div class="invora-mini-card">
                    <div class="invora-mini-title">Risk Level</div>
                    <div class="invora-mini-value">Moderate</div>
                </div>

                <div class="invora-mini-card">
                    <div class="invora-mini-title">Member Since</div>
                    <div class="invora-mini-value">
                        {{ auth()->user()->created_at->format('M Y') }}
                    </div>
                </div>

            </div>

            <!-- KYC -->

        </div>

    </div>
</div>