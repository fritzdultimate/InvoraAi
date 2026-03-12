

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

                <div class="invora-profile-status invora-credit">
                    ● Account Active
                </div>
            </div>
        </div>

        <div class="invora-kyc-badge {{ auth()->user()->kyc_status }}">
            {{ strtoupper(auth()->user()->kyc_status ?? 'UNVERIFIED') }}
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

                    <div class="invora-input full">
                        <label>Country</label>
                        <input type="text" wire:model.defer="country">
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

                <button 
                    class="invora-btn-primary"
                    wire:click="updatePassword"
                    wire:loading.attr="disabled"
                >
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
            <div class="invora-card mt-4">
                <div class="invora-card-header">
                    <i class="ri-verified-badge-fill"></i> Verification
                </div>

                <div class="invora-kyc-box">
                    <div class="invora-kyc-status">
                        {{ ucfirst(auth()->user()->kyc_status ?? 'Not Submitted') }}
                    </div>

                    <input type="file" wire:model="kyc_document">

                    <button class="invora-btn-primary">
                        Submit KYC
                    </button>
                </div>
            </div>

        </div>

    </div>
</div>

