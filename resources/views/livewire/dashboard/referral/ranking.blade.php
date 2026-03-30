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
                    {{ $currentRank?->name ?? 'Starter' }}
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
    <div class="rank-progress-card mt-4">

        <div class="progress-header">
            <div>
                <div class="progress-title">
                    Next Rank → {{ $nextRank->name }}
                </div>
                <div class="progress-sub">
                    Unlock bonus: ${{ number_format($nextRank->bonus,2) }}
                </div>
            </div>

            <div class="progress-percent">
                {{ $this->getProgress() }}%
            </div>
        </div>

        <div class="rank-progress-bar">
            <div 
                class="rank-progress-fill"
                style="width: {{ $this->getProgress() }}%"
            ></div>
        </div>

        <div class="progress-stats">

            <div>
                <span>Team Volume</span>
                <strong>
                    {{ number_format($teamVolume) }} / {{ number_format($nextRank->required_volume) }}
                </strong>
            </div>

            <div>
                <span>Direct Volume</span>
                <strong>
                    {{ number_format($directVolume) }} / {{ number_format($nextRank->direct_referrals_volume) }}
                </strong>
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

        <div class="check-item {{ $maxBonus >= $nextRank->one_time_bonus ? 'done' : '' }}">
            ✔ Deposit Bonus Requirement
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
                            +${{ number_format($rank->bonus,2) }}
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
                            Team: {{ number_format($rank->required_volume) }}
                        </div>

                        <div class="{{ $directVolume >= $rank->direct_referrals_volume ? 'ok' : '' }}">
                            Direct: {{ number_format($rank->direct_referrals_volume) }}
                        </div>

                        <div class="{{ $maxBonus >= $rank->one_time_bonus ? 'ok' : '' }}">
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