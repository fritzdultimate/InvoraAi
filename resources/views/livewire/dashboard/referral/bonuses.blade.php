<div class="invora-profile-wrapper">

    <!-- HEADER -->
    <div class="invora-profile-header">
        <div>
            <div class="invora-profile-name">
                Referral Bonuses
            </div>
            <div class="invora-profile-meta">
                Every commission earned across your 7-level network
            </div>
        </div>
    </div>

    <!-- 🔥 SUMMARY STRIP -->
    <div class="invora-bonus-summary">
        <div class="summary-box">
            <div class="summary-label">Total Bonuses</div>
            <div class="summary-value glow">
                ${{ number_format($total, 2) }}
            </div>
        </div>

        <div class="summary-box">
            <div class="summary-label">Pending</div>
            <div class="summary-value">
                ${{ number_format($bonuses->where('status','pending')->sum('amount'),2) }}
            </div>
        </div>

        <div class="summary-box">
            <div class="summary-label">Claimed</div>
            <div class="summary-value">
                ${{ number_format($bonuses->where('status','claimed')->sum('amount'),2) }}
            </div>
        </div>
    </div>


    <!-- 🔥 FILTER BAR -->
    <div class="invora-filter-bar">

        <div class="filter-group">
            <label>Status</label>
            <select wire:model.live="status">
                <option value="">All</option>
                <option value="pending">Pending</option>
                <option value="claimed">Claimed</option>
            </select>
        </div>

        <div class="filter-group">
            <label>Level</label>
            <select wire:model.live="level">
                <option value="">All</option>
                @for($i=1;$i<=7;$i++)
                    <option value="{{ $i }}">Level {{ $i }}</option>
                @endfor
            </select>
        </div>

    </div>


    <!-- 🔥 BONUS CARDS -->
    <div class="invora-bonus-grid mt-4">

        @forelse($bonuses as $bonus)
            <div class="bonus-card">

                <div class="bonus-left">
                    <div class="bonus-avatar">
                        {{ strtoupper(substr($bonus->fromUser?->name ?? 'U',0,1)) }}
                    </div>

                    <div>
                        <div class="bonus-user">
                            {{ \Illuminate\Support\Str::limit(mask(ucfirst($bonus->fromUser?->name)), 8, '') ?? 'Unknown User' }}
                        </div>

                        <div class="bonus-meta">
                            Level {{ $bonus->level }} • {{ $bonus->percent }}%
                        </div>
                    </div>
                </div>

                <div class="bonus-right">

                    <div class="bonus-amount glow">
                        ${{ number_format($bonus->amount,2) }}
                    </div>

                    @if($bonus->status === 'claimed')
                        <span class="status-badge claimed">
                            Claimed
                        </span>

                    @elseif($bonus->isClaimable())
                        <span class="status-badge locked">
                            Pending
                        </span>
                        <button
                            wire:click="claim({{ $bonus->id }})"
                            class="status-badge claimable hidden"
                            style="display: none;"
                        >
                            <span wire:loading wire:target="claim" class="spinner"></span>
                            <span wire:loading.remove wire:target="claim" style="color: #3b82f6">Claim</span>
                        </button>

                    @else
                        <span class="status-badge locked">
                            {{ $bonus->remainingTime() }}
                        </span>
                    @endif

                </div>

            </div>
        @empty
            <div class="invora-card">
                No bonuses yet.
            </div>
        @endforelse

    </div>

    <div class="mt-4">
        {{ $bonuses->links() }}
    </div>

</div>