{{--
    resources/views/livewire/dashboard/leaderboard-widget.blade.php

    Livewire component: LeaderboardWidget
    Polls every 60 seconds to stay fresh without hammering the DB.
--}}

@push('styles')
    @once
        @vite('resources/css/leaderboard.css')
    @endonce
@endpush

<div wire:poll.60s="refresh" class="lb-wrapper">

    {{-- ══════════════════════════════════════════
         NO ACTIVE CHALLENGE STATE
    ══════════════════════════════════════════ --}}
    @if(! $isActive)
        <div class="lb-card lb-empty-state">
            <div class="lb-empty-icon">
                <iconify-icon icon="mdi:trophy-outline"></iconify-icon>
            </div>
            <h4>No Active Challenge</h4>
            <p>Stay tuned — the next Leadership Challenge is coming soon.</p>
        </div>
    @else

        {{-- ══════════════════════════════════════
             HERO BANNER
        ══════════════════════════════════════ --}}
        <div class="lb-card lb-hero">

            <div class="lb-hero-shimmer"></div>

            <div class="lb-hero-body">

                {{-- Left: copy --}}
                <div class="lb-hero-copy">
                    <div class="lb-hero-eyebrow">
                        <span class="lb-live-dot"></span>
                        Live Challenge
                    </div>
                    <h2 class="lb-hero-title">{{ $challenge->name }}</h2>
                    <p class="lb-hero-sub">
                        Be among the first 3 leaders to reach
                        <strong>${{ number_format($pvTarget, 0) }} PV</strong>
                        and unlock up to <strong>$2,500 USDT</strong>,
                        2× commissions, and Founder Expansion Status.
                    </p>

                    <div class="lb-hero-meta">
                        <span>
                            <iconify-icon icon="mdi:account-group-outline"></iconify-icon>
                            {{ number_format($totalParticipants) }} participants
                        </span>
                        <span>
                            <iconify-icon icon="mdi:calendar-clock-outline"></iconify-icon>
                            Ends {{ $challenge->end_at->format('M d, Y') }}
                        </span>
                    </div>
                </div>

                {{-- Right: countdown --}}
                <div class="lb-hero-countdown-wrap">
                    <x-leaderboard.countdown :endsAt="$endsAt" />
                </div>

            </div>
        </div>

        {{-- ══════════════════════════════════════
             MY PROGRESS CARD
        ══════════════════════════════════════ --}}
        <div class="lb-card lb-my-progress">

            <div class="lb-section-header">
                <div class="lb-section-icon">
                    <iconify-icon icon="mdi:lightning-bolt"></iconify-icon>
                </div>
                <div>
                    <h3>Your Progress</h3>
                    <span>Personal Volume toward ${{ number_format($pvTarget, 0) }} target</span>
                </div>

                @if($myEntry && $myEntry['rank'])
                    <div class="lb-my-rank-badge">
                        <iconify-icon icon="mdi:podium-gold"></iconify-icon>
                        Rank #{{ $myEntry['rank'] }}
                    </div>
                @endif
            </div>

            <x-leaderboard.progress-bar
                :current="$myPV"
                :target="$pvTarget"
                :percentage="$progressPct"
                :remaining="$remainingPV"
            />

        </div>

        {{-- ══════════════════════════════════════
             PRIZES ROW
        ══════════════════════════════════════ --}}
        <div class="lb-prizes-row">
            @foreach($prizes as $i => $prize)
                <div class="lb-prize-chip lb-prize-{{ $i + 1 }}">
                    <span class="lb-prize-pos">
                        {{ ['🥇','🥈','🥉'][$i] ?? '#'.($i+1) }}
                    </span>
                    <span class="lb-prize-amount">
                        ${{ number_format(data_get($prize, 'cash', 0)) }} USDT
                    </span>
                    <span class="lb-prize-perk">+ 2× Commissions · Founder Badge</span>
                </div>
            @endforeach
        </div>

        {{-- ══════════════════════════════════════
             GLOBAL LEADERBOARD
        ══════════════════════════════════════ --}}
        <div class="lb-card lb-board">

            <div class="lb-section-header">
                <div class="lb-section-icon lb-section-icon--gold">
                    <iconify-icon icon="mdi:trophy"></iconify-icon>
                </div>
                <div>
                    <h3>Global Leaderboard</h3>
                    <span>First 3 to reach ${{ number_format($pvTarget, 0) }} PV win</span>
                </div>
            </div>

            <div class="lb-board-list">

                @forelse($topEntries as $entry)
                    <x-leaderboard.rank-card
                        :rank="$entry['rank']"
                        :name="$entry['name']"
                        :avatar="$entry['avatar']"
                        :score="$entry['score']"
                        :target="$pvTarget"
                        :rankChange="$entry['rank_change']"
                        :completed="$entry['completed']"
                        :prize="$prizes[$entry['rank'] - 1] ?? null"
                        :isCurrentUser="$myEntry && $myEntry['rank'] === $entry['rank']"
                    />
                @empty
                    {{-- Skeleton placeholders --}}
                    @for($i = 1; $i <= 3; $i++)
                        <div class="lb-rank-card lb-skeleton">
                            <div class="lb-rank-pos lb-skeleton-block" style="width:32px;height:32px;border-radius:50%"></div>
                            <div class="lb-skeleton-block" style="width:40px;height:40px;border-radius:50%"></div>
                            <div style="flex:1;display:flex;flex-direction:column;gap:6px">
                                <div class="lb-skeleton-block" style="width:120px;height:13px;border-radius:6px"></div>
                                <div class="lb-skeleton-block" style="width:80px;height:11px;border-radius:6px"></div>
                            </div>
                            <div class="lb-skeleton-block" style="width:90px;height:28px;border-radius:8px"></div>
                        </div>
                    @endfor
                    <p class="lb-empty-sub">No leaders yet — be the first to qualify!</p>
                @endforelse

                {{-- Pad to 3 slots if fewer winners exist --}}
                @for($i = count($topEntries) + 1; $i <= 3; $i++)
                    <div class="lb-rank-card lb-rank-card--open">
                        <div class="lb-rank-pos lb-rank-pos--{{ $i }}">{{ $i }}</div>
                        <div class="lb-avatar lb-avatar--ghost">
                            <iconify-icon icon="mdi:account-question-outline"></iconify-icon>
                        </div>
                        <div class="lb-rank-info">
                            <span class="lb-rank-name">Position {{ $i }} Open</span>
                            <span class="lb-rank-sub">Could be you</span>
                        </div>
                        <div class="lb-open-tag">Available</div>
                    </div>
                @endfor

            </div>
        </div>

        {{-- ══════════════════════════════════════
             COMMISSION BOOSTER REFERENCE
        ══════════════════════════════════════ --}}
        <div class="lb-card lb-booster">

            <div class="lb-section-header">
                <div class="lb-section-icon lb-section-icon--green">
                    <iconify-icon icon="mdi:rocket-launch-outline"></iconify-icon>
                </div>
                <div>
                    <h3>2× Commission Booster</h3>
                    <span>Winners enjoy doubled commissions for 30 days</span>
                </div>
            </div>

            <div class="lb-booster-grid">
                @php
                    $levels = [
                        ['level' => 1, 'std' => '10%',    'boosted' => '20%'],
                        ['level' => 2, 'std' => '5%',     'boosted' => '10%'],
                        ['level' => 3, 'std' => '2.5%',   'boosted' => '5%'],
                        ['level' => 4, 'std' => '1.5%',   'boosted' => '3%'],
                        ['level' => 5, 'std' => '1%',     'boosted' => '2%'],
                        ['level' => 6, 'std' => '0.5%',   'boosted' => '1%'],
                        ['level' => 7, 'std' => '0.25%',  'boosted' => '0.5%'],
                    ];
                @endphp

                @foreach($levels as $l)
                    <div class="lb-booster-row">
                        <span class="lb-booster-level">L{{ $l['level'] }}</span>
                        <span class="lb-booster-std">{{ $l['std'] }}</span>
                        <iconify-icon icon="mdi:arrow-right" class="lb-booster-arrow"></iconify-icon>
                        <span class="lb-booster-boosted">{{ $l['boosted'] }}</span>
                    </div>
                @endforeach
            </div>

        </div>

    @endif

</div>

@push('scripts')
<script>
    // Countdown timer — initialised by the countdown blade component
    // See: resources/views/components/leaderboard/countdown.blade.php
</script>
@endpush