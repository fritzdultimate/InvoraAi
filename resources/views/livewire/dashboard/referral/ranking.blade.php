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

                    @unless (auth()->user()->isActive())
                        <span x-data="{ open: false }" class="relative flex items-center" x-cloak style="position:relative">
                            <iconify-icon icon="solar:info-circle-outline" style="font-size:20px;"
                                class="cursor-pointer text-gray-400 hover:text-white transition"
                                @click="open = !open"></iconify-icon>

                            <div x-show="open" @click.outside="open = false" x-transition class="invora-tooltip invora-rank-tooltip">

                                <div class="title">Why is my account unranked?</div>

                                <div class="text">
                                    Your account is currently <strong>unranked</strong> because you haven’t met the required
                                    <strong>team volume</strong> and <strong>direct referral volume</strong>.
                                </div>

                                <div class="text" style="margin-top:8px;">
                                    Unlock your first rank by building your network and increasing your trading activity.
                                </div>

                                <!-- ACTIVATION NOTE -->
                                <div class="highlight-box warning hidden">
                                    ⚠️ Team volume tracking only starts after you generate at least 
                                    <strong>$500</strong> in trading volume within 30 days.
                                </div>

                                <!-- HOW TO -->
                                <div class="steps">
                                    <div class="step">
                                        <span>1</span>
                                        Build your team volume
                                    </div>

                                    <div class="step">
                                        <span>2</span>
                                        Meet direct referral volume requirements
                                    </div>
                                </div>

                                <div class="rank-legend">
                                    <span><strong>TV</strong> = Team Volume</span>
                                    <span><strong>DV</strong> = Direct Referral Volume</span>
                                </div>

                                <div class="rank-ladder">

                                    <div class="rank-item active">
                                        <div class="rank-name">Amateur</div>
                                        <div class="rank-meta">
                                            <span>TV: $4,999</span>
                                            <span>DV: $1,499</span>
                                        </div>
                                    </div>

                                    <div class="rank-item">
                                        <div class="rank-name">Bronze</div>
                                        <div class="rank-meta">
                                            <span>TV: $9,999</span>
                                            <span>DV: $2,499</span>
                                        </div>
                                    </div>

                                    <div class="rank-item">
                                        <div class="rank-name">Achiever</div>
                                        <div class="rank-meta">
                                            <span>TV: $19,999</span>
                                            <span>DV: $4,999</span>
                                        </div>
                                    </div>

                                    <div class="rank-item">
                                        <div class="rank-name">Silver</div>
                                        <div class="rank-meta">
                                            <span>TV: $49,999</span>
                                            <span>DV: $7,499</span>
                                        </div>
                                    </div>

                                </div>

                                <div class="highlight-box" style="margin-top:10px;">
                                    💰 Each rank unlocks exclusive one-time rewards and daily earning bonuses.
                                </div>

                                <!-- CTA -->
                                <a href="{{ route('bot') }}" class="invora-mini-btn">
                                    Start Building Rank →
                                </a>

                            </div>
                        </span>
                    @endunless
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


                <!-- DIRECT VOLUME -->
                <div class="stat-card">
                    <div class="stat-top">
                        <span class="stat-title">Direct Volume</span>
                        <span class="stat-badge {{ $directVolume >= $nextRank->direct_referrals_volume ? 'done' : '' }}">
                            {{ $directVolume >= $nextRank->direct_referrals_volume ? '✓' : '...' }}
                        </span>
                    </div>

                    <div class="stat-main">
                        ${{ number_format($directVolume) }}
                    </div>

                    <div class="stat-sub">
                        of ${{ number_format($nextRank->direct_referrals_volume) }}
                    </div>

                    <div class="mini-bar">
                        <div 
                            class="mini-fill"
                            style="width: {{ min(100, ($directVolume / $nextRank->direct_referrals_volume) * 100) }}%"
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

        <div class="check-item {{ $directVolume >= $nextRank->direct_referrals_volume ? 'done' : '' }}">
            ✔ Direct Referral Volume
        </div>

    </div>
    @endif


    <!-- 💰 BONUS HISTORY -->
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

                        <div class="{{ $directVolume >= $rank->direct_referrals_volume ? 'ok' : '' }}">
                            Direct: ${{ number_format($rank->direct_referrals_volume) }}
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