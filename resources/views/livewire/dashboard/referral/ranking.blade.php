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

        .invora-rank-tooltip {
    width: 280px;
}

        .rank-ladder {
            margin-top: 12px;
            display: flex;
            flex-direction: column;
            gap: 6px;
            max-height: 180px;
            overflow-y: auto;
            padding-right: 4px;
        }

        .rank-item {
            display: flex;
            justify-content: space-between;
            align-items: center;

            padding: 8px 10px;
            border-radius: 10px;

            background: #020617;
            border: 1px solid rgba(255,255,255,0.05);

            font-size: 12px;
        }

        .rank-item.active {
            border: 1px solid rgba(34,197,94,0.4);
            background: rgba(34,197,94,0.08);
        }

        .rank-name {
            color: #e2e8f0;
            font-weight: 500;
        }

        .rank-values {
            color: #64748b;
            font-size: 11px;
        }

        /* Steps */
        .steps {
            margin-top: 10px;
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .step {
            display: flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            color: #e2e8f0;
        }

        .step span {
            width: 18px;
            height: 18px;
            border-radius: 50%;
            background: #22c55e;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            color: #fff;
        }

        /* Warning box */
        .highlight-box.warning {
            background: rgba(245,158,11,0.08);
            border: 1px solid rgba(245,158,11,0.2);
            color: #f59e0b;
        }

        .rank-meta {
            display: flex;
            gap: 8px;
            font-size: 11px;
            color: #94a3b8;
        }

        .rank-meta span {
            background: rgba(255,255,255,0.05);
            padding: 3px 6px;
            border-radius: 6px;
        }

        .rank-legend {
            margin-top: 10px;
            margin-bottom: 6px;
            font-size: 11px;
            color: #64748b;
            display: flex;
            flex-direction: column;
            gap: 2px;
        }
        .rank-reqs-head {
            margin-top: 20px;
            padding-top: 16px;
            border-top: 1px solid rgba(255,255,255,0.06);
            font-size: 11px;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #94a3b8;
        }

    </style>
@endpush

<div class="invora-profile-wrapper">

    <!-- 🔥 HEADER -->
    <div class="invora-profile-header">
        <div>
            <div class="invora-profile-name">Rank System</div>
            <div class="invora-profile-meta">
                Your status, achievements & next milestone
            </div>
        </div>
    </div>

    <!-- 🏆 HERO (STATUS) -->
    <div class="rank-hero">

        <div class="rank-top">
            <div>
                <div class="rank-title flex items-center gap-2 mt-3">
                    {{ $currentRank?->name ?? 'Unranked' }}

                    <span x-data="{ open: false }" class="relative flex items-center" x-cloak style="position:relative">
                        <iconify-icon icon="solar:info-circle-outline" style="font-size:20px;"
                            class="cursor-pointer text-gray-400 hover:text-white transition"
                            @click="open = !open"></iconify-icon>

                        <div x-show="open" @click.outside="open = false" x-transition class="invora-tooltip invora-rank-tooltip">

                            @if($currentRank)
                                <div class="title">How you reached {{ $currentRank->name }}</div>

                                <div class="text">
                                    You reached <strong>{{ $currentRank->name }}</strong> by building a team volume of at least
                                    <strong>${{ number_format($currentRank->required_volume) }}</strong>.
                                </div>

                                <div class="text" style="margin-top:8px;">
                                    Reward: <strong>+${{ number_format($currentRank->one_time_bonus, 2) }}</strong> one-time
                                    @if($currentRank->bonus > 0)
                                        and <strong>${{ number_format($currentRank->bonus, 2) }}</strong> daily
                                    @endif
                                </div>
                            @else
                                <div class="title">Why is my account unranked?</div>

                                <div class="text">
                                    Your account is currently <strong>unranked</strong> because you haven’t met the required
                                    <strong>team volume</strong>.
                                </div>

                                <div class="text" style="margin-top:8px;">
                                    Unlock your first rank by building your network and increasing your trading activity.
                                </div>
                            @endif

                            <div class="rank-legend">
                                <span><strong>TV</strong> = Team Volume needed for each rank</span>
                            </div>

                            <div class="rank-ladder">
                                @foreach($ranks as $r)
                                    <div class="rank-item {{ $currentRank && $r->level <= $currentRank->level ? 'active' : '' }}">
                                        <div class="rank-name">{{ $r->name }}</div>
                                        <div class="rank-meta">
                                            <span>TV: ${{ number_format($r->required_volume) }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>

                            <div class="highlight-box" style="margin-top:10px;">
                                💰 Each rank unlocks exclusive one-time rewards and daily earning bonuses.
                            </div>

                            @unless($currentRank)
                                <a href="{{ route('bot') }}" class="invora-mini-btn">
                                    Start Building Rank →
                                </a>
                            @endunless

                        </div>
                    </span>
                </div>

                <div class="rank-sub">
                    Level {{ $currentRank?->level ?? 0 }}
                    • Achieved {{ optional($user->rank?->achieved_at)->format('M d, Y') ?? '—' }}
                </div>

            </div>

            <div class="rank-total-earned">
                ${{ number_format($bonuses->sum('amount'),2) }}
                <span>Total Earned</span>
            </div>

        </div>

        @if($currentRank)
            @php
                $reqs = [
                    ['Team Volume', $teamVolume, $currentRank->required_volume, true],
                    ['Direct Referrals Volume', $directVolume, $currentRank->direct_referrals_volume, true],
                    ['Personal Deposits', $personalDeposits, $currentRank->deposits, true],
                    ['Direct Referrals', $directReferrals, $currentRank->direct_referrals, false],
                ];
            @endphp

            <div class="rank-reqs-head">Requirements for {{ $currentRank->name }}</div>

            <div class="rank-stats">
                @foreach($reqs as [$label, $yours, $needed, $isMoney])
                    @php
                        $met = $yours >= $needed;
                        $pct = $needed > 0 ? min(100, ($yours / $needed) * 100) : 100;
                    @endphp
                    <div class="stat-card">
                        <div class="stat-top">
                            <span class="stat-title">{{ $label }}</span>
                            <span class="stat-badge {{ $met ? 'done' : '' }}">{{ $met ? '✓ Met' : 'Not met' }}</span>
                        </div>

                        <div class="stat-main">
                            {{ $isMoney ? '$' : '' }}{{ number_format($yours) }}
                        </div>

                        <div class="stat-sub">
                            of {{ $isMoney ? '$' : '' }}{{ number_format($needed) }} required
                        </div>

                        <div class="mini-bar">
                            <div class="mini-fill" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

    </div>


    <!-- 🚀 PROGRESSION -->
    @if($nextRank)

        <div class="rank-next-card mt-4">

            <!-- 🔥 TOP (FOCUS AREA) -->
            <div class="rank-next-header">

                <div class="rank-next-left">
                    <div class="next-label">NEXT RANK</div>

                    <div class="next-name">
                        {{ $nextRank->name }}
                    </div>

                    <div class="next-reward">
                        Earn +${{ number_format($nextRank->one_time_bonus,2) }}
                    </div>
                </div>

                <div class="rank-progress-circle">
                    <div 
                        class="circle-progress"
                        style="--progress: {{ $this->getProgress() }};"
                    >
                        <span>{{ $this->getProgress() }}%</span>
                    </div>
                </div>

            </div>

            <!-- 🔥 PROGRESS BAR (GLOW) -->
            <div class="rank-bar-wrap">
                <div class="rank-bar">
                    <div 
                        class="rank-bar-fill"
                        style="width: {{ $this->getProgress() }}%"
                    ></div>
                </div>
            </div>

            <!-- 🔥 EMOTIONAL MESSAGE -->
            <div class="rank-message">
                You’re 
                <strong>
                    ${{ number_format(max(0, $nextRank->required_volume - $teamVolume)) }}
                </strong> 
                away from 
                <span>{{ $nextRank->name }}</span>
            </div>

            <!-- 🔥 STATS -->

            <div class="rank-stats">

                <!-- TEAM VOLUME -->
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-title">Team Volume</span>
                        <span class="stat-badge {{ $teamVolume >= $nextRank->required_volume ? 'done' : '' }}">
                            {{ $teamVolume >= $nextRank->required_volume ? '✓' : '...' }}
                        </span>
                    </div>

                    <div class="stat-main">
                        ${{ number_format($teamVolume) }}
                    </div>

                    <div class="stat-sub">
                        of ${{ number_format($nextRank->required_volume) }}
                    </div>

                    <div class="mini-bar">
                        <div 
                            class="mini-fill"
                            style="width: {{ min(100, ($teamVolume / $nextRank->required_volume) * 100) }}%"
                        ></div>
                    </div>
                </div>


                

            </div>

        </div>

    @endif


    <!-- ✅ REQUIREMENTS CHECK -->
    @if($nextRank)
    <div class="rank-checklist mt-4">

        <div class="check-item {{ $teamVolume >= $nextRank->required_volume ? 'done' : '' }}">
            ✔ Team Volume Requirement
        </div>

    </div>
    @endif


    <!-- BONUS HISTORY -->
    <div class="rank-section mt-4">

        <div class="section-title">Rank Earnings</div>

        <div class="invora-bonus-grid">

            @forelse($bonuses as $bonus)
                <div class="bonus-card">

                    <div>
                        <div class="bonus-user">
                            {{ $bonus->title ?? 'Rank Bonus' }}
                        </div>

                        <div class="bonus-meta">
                            {{ $bonus->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <div class="bonus-amount glow">
                        +${{ number_format($bonus->amount,2) }}
                    </div>

                     @if($bonus->status === 'credited')
                        <span class="status-badge claimed">
                            Claimed
                        </span>
                    @endif

                </div>
            @empty
                <div class="invora-card">
                    No earnings yet.
                </div>
            @endforelse

        </div>

    </div>

    <!--RESIDUAL HISTORY  -->
    <div class="rank-section mt-4">

        <div class="section-title">Rank Residual Earnings</div>

        <div class="invora-bonus-grid">

            @forelse($daily_bonuses as $bonus)
                <div class="bonus-card">

                    <div>
                        <div class="bonus-user">
                            {{ $bonus->title ?? 'Residual Bonus' }}
                        </div>

                        <div class="bonus-meta">
                            {{ $bonus->created_at->diffForHumans() }}
                        </div>
                    </div>

                    <div class="bonus-amount glow">
                        +${{ number_format($bonus->amount,2) }}
                    </div>

                    @if($bonus->status === 'credited')
                        <span class="status-badge claimed">
                            Claimed
                        </span>
                    @endif

                </div>
            @empty
                <div class="invora-card">
                    No earnings yet.
                </div>
            @endforelse

        </div>

    </div>

    <div class="rank-ladder mt-4">

        <div class="rank-message hidden">
            You need 
            <strong>${{ number_format(max(0, $nextRank->required_volume - $teamVolume)) }}</strong> 
            more volume to reach 
            <strong>{{ $nextRank->name }}</strong>
        </div>

        @foreach($ranks as $rank)

            @php
                $isCurrent = $currentRank && $rank->id === $currentRank->id;
                $isCompleted = $currentRank && $rank->level < $currentRank->level;
                $isNext = $nextRank && $rank->id === $nextRank->id;
            @endphp

            <div class="rank-item 
                {{ $isCompleted ? 'completed' : '' }} 
                {{ $isCurrent ? 'current' : '' }} 
                {{ !$isCompleted && !$isCurrent ? 'locked' : '' }}
            ">

                <!-- LEFT LINE -->
                <div class="rank-line">
                    <div class="rank-dot"></div>
                </div>

                <!-- CONTENT -->
                <div class="rank-card">

                    <!-- TOP -->
                    <div class="rank-card-top">
                        <div>
                            <div class="rank-name">
                                {{ $rank->name }}
                            </div>

                            <div class="rank-meta">
                                Level {{ $rank->level }}
                            </div>
                        </div>

                        <div class="rank-bonus">
                            +${{ number_format($rank->one_time_bonus,2) }}
                        </div>
                    </div>

                    <!-- STATUS BADGE -->
                    <div class="rank-status">
                        @if($isCompleted)
                            <span class="badge success">Achieved ✔</span>
                        @elseif($isCurrent)
                            <span class="badge active">Current Rank</span>
                        @else
                            <span class="badge locked">Locked</span>
                        @endif
                    </div>

                    <!-- REQUIREMENTS -->
                    <div class="rank-req">

                        <div class="{{ $teamVolume >= $rank->required_volume ? 'ok' : '' }}">
                            Team: ${{ number_format($rank->required_volume) }}
                        </div>

                        <div class="{{ $maxBonus >= $rank->one_time_bonus ? 'ok' : '' }} hidden">
                            Bonus: {{ number_format($rank->one_time_bonus) }}
                        </div>

                    </div>

                    <!-- PROGRESS (ONLY CURRENT/NEXT) -->
                    @if($isCurrent || $isNext)
                        @php
                            $progress = min(($teamVolume / $rank->required_volume) * 100, 100);
                        @endphp

                        <div class="rank-progress-bar mt-2">
                            <div 
                                class="rank-progress-fill"
                                style="width: {{ $progress }}%"
                            ></div>
                        </div>

                        <div class="rank-progress-text">
                            {{ round($progress) }}% completed
                        </div>
                    @endif

                </div>

            </div>

        @endforeach

    </div>

</div>