<div>
    <div wire:poll.10000ms="loadAlert"></div>
    @if($show && $currentAlert)
        <div
            wire:key="milestone-popup-{{ $currentAlert->id }}"
            style="
                position: fixed;
                bottom: 24px;
                right: 24px;
                z-index: 9999;
                width: 320px;
                background: linear-gradient(145deg, rgba(39,49,66,.97), rgba(20,25,40,.97));
                border: 1px solid rgba(99,102,241,.35);
                border-radius: var(--radius);
                box-shadow: 0 20px 60px rgba(0,0,0,.6), inset 0 1px 0 rgba(255,255,255,.03);
                overflow: hidden;
                animation: lb-popup-in .3s ease;
            "
        >
            @php
                $amount      = number_format($currentAlert->milestone_amount);
                $isTeam      = $currentAlert->type === 'team';
                $isCompleted = $currentAlert->milestone_amount === 10000;
                $progress    = ($currentAlert->milestone_amount / 10000) * 100;
            @endphp

            {{-- Shimmer stripe (reuses existing lb-hero-shimmer animation) --}}
            <div class="lb-hero-shimmer"></div>

            {{-- Header --}}
            <div style="
                padding: 14px 16px;
                background: linear-gradient(135deg, rgba(99,102,241,.25), rgba(99,102,241,.08));
                border-bottom: 1px solid var(--border-soft);
                display: flex;
                align-items: center;
                justify-content: space-between;
            ">
                <div style="display: flex; align-items: center; gap: 8px;">
                    <div class="lb-section-icon lb-section-icon--gold" style="width: 28px; height: 28px; font-size: 14px;">
                        🏆
                    </div>
                    <div>
                        <div style="font-size: 11px; font-weight: 700; letter-spacing: .7px; text-transform: uppercase; color: var(--accent);">
                            Milestone Reached
                        </div>
                        <div style="font-size: 10px; color: var(--text-secondary); margin-top: 1px;">
                            <span class="lb-live-dot" style="width: 5px; height: 5px; margin-right: 4px;"></span>
                            {{ $isTeam ? 'Team achievement' : 'Personal achievement' }}
                        </div>
                    </div>
                </div>
                <button
                    wire:click="dismiss"
                    style="
                        background: none;
                        border: none;
                        color: var(--text-secondary);
                        font-size: 18px;
                        cursor: pointer;
                        padding: 0 2px;
                        line-height: 1;
                        transition: color .2s;
                    "
                    onmouseover="this.style.color='var(--text-primary)'"
                    onmouseout="this.style.color='var(--text-secondary)'"
                >&times;</button>
            </div>

            {{-- Body --}}
            <div style="padding: 16px;">

                {{-- Amount --}}
                <div style="margin-bottom: 14px;">
                    <div style="font-size: 11px; color: var(--text-secondary); margin-bottom: 4px;">
                        {{ $isTeam ? $currentAlert->challenge->name . ' — a participant has reached' : 'You have reached' }}
                    </div>
                    <div style="display: flex; align-items: baseline; gap: 8px;">
                        <span style="font-size: 28px; font-weight: 700; color: var(--green); line-height: 1;">
                            ${{ $amount }}
                        </span>
                        @if($isCompleted)
                            <span style="font-size: 12px; color: var(--green); font-weight: 600;">✓ Complete!</span>
                        @endif
                    </div>
                    <div style="font-size: 11px; color: var(--text-secondary); margin-top: 4px;">
                        {{ $currentAlert->challenge->name ?? 'Challenge' }}
                    </div>
                </div>

                {{-- Mini milestone progress track (reuses .lb-progress-track styles) --}}
                <div style="margin-bottom: 16px;">
                    <div class="lb-progress-track" style="height: 6px;">
                        <div
                            class="lb-progress-fill {{ $isCompleted ? 'lb-progress-fill--done' : '' }}"
                            style="width: {{ $progress }}%;"
                        >
                            <div class="lb-progress-shimmer"></div>
                        </div>
                        {{-- Milestone markers --}}
                        @foreach([25, 50, 75] as $pct)
                            <div class="lb-progress-marker" style="left: {{ $pct }}%;">
                                <span></span>
                            </div>
                        @endforeach
                    </div>
                    <div style="display: flex; justify-content: space-between; margin-top: 5px;">
                        @foreach([2500, 5000, 7500, 10000] as $m)
                            <span style="font-size: 9px; color: {{ $currentAlert->milestone_amount >= $m ? 'var(--green)' : 'var(--text-secondary)' }};">
                                ${{ number_format($m) }}
                            </span>
                        @endforeach
                    </div>
                </div>

                {{-- CTA --}}
                <button
                    wire:click="goToLeaderboard"
                    style="
                        width: 100%;
                        padding: 10px;
                        border-radius: calc(var(--radius) - 4px);
                        background: rgba(99,102,241,.15);
                        border: 1px solid rgba(99,102,241,.35);
                        color: var(--accent);
                        font-size: 13px;
                        font-weight: 600;
                        cursor: pointer;
                        transition: all .2s ease;
                        display: flex;
                        align-items: center;
                        justify-content: center;
                        gap: 6px;
                    "
                    onmouseover="this.style.background='rgba(99,102,241,.25)'"
                    onmouseout="this.style.background='rgba(99,102,241,.15)'"
                >
                    View Leaderboard
                    <span style="font-size: 15px;">→</span>
                </button>
            </div>
        </div>

        <style>
            @keyframes lb-popup-in {
                from { opacity: 0; transform: translateY(16px); }
                to   { opacity: 1; transform: translateY(0);    }
            }
        </style>
    @endif
</div>