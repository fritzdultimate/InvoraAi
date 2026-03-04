<div class="invora-profile-wrapper">

    <!-- HEADER -->
    <div class="invora-profile-header">
        <div>
            <div class="invora-profile-name">
                Direct Referrals
            </div>
            <div class="invora-profile-meta">
                Your Level 1 network — your strongest earning layer
            </div>
        </div>
    </div>


    <!-- 🔥 SUMMARY STRIP -->
    <div class="invora-direct-summary">

        <div class="summary-box">
            <div class="summary-label">Total Directs</div>
            <div class="summary-value">
                {{ $directs->total() }}
            </div>
        </div>

        <div class="summary-box">
            <div class="summary-label">Level 1 Earnings</div>
            <div class="summary-value glow">
                ${{ number_format($totalEarnings, 2) }}
            </div>
        </div>

    </div>


    <!-- 🔥 DIRECT CARDS GRID -->
    <div class="invora-direct-grid mt-4">

        @forelse($directs as $referral)
            @php
                $user = $referral->user;
                $generated = \App\Models\ReferralBonus::where('user_id', auth()->id())
                    ->where('from_user_id', $user->id)
                    ->sum('amount');
            @endphp

            <div class="direct-card">

                <!-- LEFT SIDE -->
                <div class="direct-left">

                    <div class="direct-avatar">
                        {{ strtoupper(substr($user->name,0,1)) }}
                    </div>

                    <div>
                        <div class="direct-name">
                            {{ $user->name }}
                        </div>

                        <div class="direct-meta">
                            Joined {{ $user->created_at->format('M d, Y') }}
                        </div>
                    </div>

                </div>

                <!-- RIGHT SIDE -->
                <div class="direct-right">

                    <div class="direct-stat">
                        <span>Generated</span>
                        <strong class="glow">
                            ${{ number_format($generated,2) }}
                        </strong>
                    </div>

                    <div class="direct-stat">
                        <span>Status</span>

                        @if($user->deposit_balance > 0)
                            <span class="status-pill active">Active</span>
                        @else
                            <span class="status-pill inactive">Inactive</span>
                        @endif
                    </div>

                </div>

            </div>

        @empty
            <div class="invora-card">
                No direct referrals yet.
            </div>
        @endforelse

    </div>

    <div class="mt-4">
        {{ $directs->links() }}
    </div>

</div>