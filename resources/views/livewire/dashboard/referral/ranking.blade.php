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
                <div class="rank-title">
                    {{ $currentRank?->name ?? 'Unranked' }}
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