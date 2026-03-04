<div class="invora-profile-wrapper">


    <!-- HEADER -->
    <div class="invora-profile-header">
        <div>
            <div class="invora-profile-name">
                Referral Center
            </div>

            <div class="invora-profile-meta">
                Grow your network • Earn from 7 levels • Scale infinitely
            </div>
        </div>

        <div class="invora-kyc-badge verified">
            ACTIVE
        </div>
    </div>


    <!-- 🔥 STATS GRID -->
    <div class="invora-ref-grid">

        <div class="invora-stat-card">
            <div class="stat-label">Direct Referrals</div>
            <div class="stat-value">
                {{ $referral->total_direct_referrals ?? 0 }}
            </div>
        </div>

        <div class="invora-stat-card">
            <div class="stat-label">Total Downlines</div>
            <div class="stat-value">
                {{ $referral->total_downlines ?? 0 }}
            </div>
        </div>

        <div class="invora-stat-card highlight">
            <div class="stat-label">Total Earnings</div>
            <div class="stat-value glow">
                ${{ number_format($referral->total_earnings ?? 0, 2) }}
            </div>
        </div>

        <div class="invora-stat-card">
            <div class="stat-label">Pending Bonuses</div>
            <div class="stat-value">
                ${{ number_format($pendingAmount, 2) }}
            </div>
        </div>

        <div class="invora-stat-card">
            <div class="stat-label">Claimable Now</div>
            <div class="stat-value glow">
                ${{ number_format($claimableAmount, 2) }}
            </div>

            @if($claimableAmount > 0)
                <button
                    wire:click="claimAll"
                    wire:loading.attr="disabled"
                    class="invora-btn-primary mt-2"
                >
                    Claim All
                </button>
            @endif
        </div>

    </div>


    <!-- 🔥 REFERRAL LINK SECTION -->
    <div class="invora-card mt-4">

        <div class="invora-card-header">
            Your Referral Link
        </div>

        <div 
            x-data="{
                copied:false,
                copyLink() {
                    const text = this.$refs.link.value;
                    navigator.clipboard.writeText(text);
                    this.copied = true;
                    setTimeout(() => this.copied = false, 2000);
                }
            }"
            class="invora-copy-box"
        >

            <input 
                type="text"
                x-ref="link"
                value="{{ url('/register?ref=' . auth()->user()->affiliate_code) }}"
                readonly
                @click="copyLink()"
                class="invora-copy-input"
            >

            <button 
                @click="copyLink()"
                class="invora-copy-btn"
            >
                Copy
            </button>

            <span 
                x-show="copied"
                x-transition
                class="copy-success"
            >
                Copied ✓
            </span>

        </div>

    </div>

</div>