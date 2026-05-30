{{--
    resources/views/components/leaderboard/progress-bar.blade.php

    Props:
        float  $current     – user's current PV
        float  $target      – PV target
        float  $percentage  – pre-calculated %
        float  $remaining   – remaining PV needed
--}}

@props([
    'current'    => 0,
    'target'     => 10000,
    'percentage' => 0,
    'remaining'  => 10000,
])

<div class="lb-progress-wrap">

    {{-- Labels row --}}
    <div class="lb-progress-labels">
        <span class="lb-progress-current">
            <iconify-icon icon="mdi:chart-line-variant"></iconify-icon>
            ${{ number_format($current, 2) }} PV
        </span>
        <span class="lb-progress-pct {{ $percentage >= 100 ? 'lb-progress-pct--done' : '' }}">
            {{ $percentage }}%
        </span>
    </div>

    {{-- Track --}}
    <div class="lb-progress-track" role="progressbar" aria-valuenow="{{ $percentage }}" aria-valuemin="0" aria-valuemax="100">
        <div
            class="lb-progress-fill {{ $percentage >= 100 ? 'lb-progress-fill--done' : '' }}"
            style="width: {{ $percentage }}%"
        >
            {{-- Shimmer --}}
            <div class="lb-progress-shimmer"></div>
        </div>

        {{-- Target marker --}}
        <div class="lb-progress-marker" style="left: 100%">
            <span>$10K</span>
        </div>
    </div>

    {{-- Bottom row --}}
    <div class="lb-progress-footer">
        @if($percentage >= 100)
            <span class="lb-progress-done">
                <iconify-icon icon="mdi:check-circle-outline"></iconify-icon>
                Target reached — you qualify!
            </span>
        @else
            <span class="lb-progress-remaining">
                <iconify-icon icon="mdi:target"></iconify-icon>
                ${{ number_format($remaining, 2) }} more to qualify
            </span>
        @endif

        <span class="lb-progress-target">
            Target: ${{ number_format($target, 0) }}
        </span>
    </div>

</div>