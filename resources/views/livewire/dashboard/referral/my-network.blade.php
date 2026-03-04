<div class="invora-profile-wrapper">

    <div class="invora-profile-header">
        <div>
            <div class="invora-profile-name">
                My Network
            </div>
            <div class="invora-profile-meta">
                Explore your 10-level referral structure
            </div>
        </div>
    </div>

    <!-- 🔥 LEVEL SELECTOR -->
    <div class="invora-level-tabs">
        @for($i=1;$i<=10;$i++)
            <button
                wire:click="$set('depth', {{ $i }})"
                class="level-tab {{ $depth == $i ? 'active' : '' }}"
            >
                Level {{ $i }}
            </button>
        @endfor
    </div>

    <!-- 🔥 SUMMARY -->
    <div class="invora-direct-summary mt-3">

        <div class="summary-box">
            <div class="summary-label">Users in Level {{ $depth }}</div>
            <div class="summary-value">
                {{ $network->total() }}
            </div>
        </div>

        <div class="summary-box">
            <div class="summary-label">Earnings from Level {{ $depth }}</div>
            <div class="summary-value glow">
                ${{ number_format($earnings,2) }}
            </div>
        </div>

    </div>

    <!-- 🔥 USER CARDS -->
    <div class="invora-direct-grid mt-4">

        @forelse($network as $ref)
            @php $user = $ref->user; @endphp

            <div class="direct-card">

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

                <div class="direct-right">
                    @if($user->deposit_balance > 0)
                        <span class="status-pill active">Active</span>
                    @else
                        <span class="status-pill inactive">Inactive</span>
                    @endif
                </div>

            </div>

        @empty
            <div class="invora-card">
                No users found in this level.
            </div>
        @endforelse

    </div>

    <div class="mt-4">
        {{ $network->links() }}
    </div>

</div>