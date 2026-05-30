{{--
    resources/views/components/leaderboard/rank-card.blade.php

    Props:
        int         $rank           – 1 / 2 / 3
        string      $name
        string|null $avatar         – path / URL to profile photo
        float       $score          – current PV score
        float       $target         – PV target
        int         $rankChange     – positive = moved up, negative = moved down
        bool        $completed      – has hit the target
        array|null  $prize          – ['cash' => 2500, 'label' => '...']
        bool        $isCurrentUser  – highlight the auth user's own row
--}}

@props([
    'rank'          => 1,
    'name'          => 'Leader',
    'avatar'        => null,
    'score'         => 0,
    'target'        => 10000,
    'rankChange'    => 0,
    'completed'     => false,
    'prize'         => null,
    'isCurrentUser' => false,
])

@php
    $pct       = $target > 0 ? min(100, round(($score / $target) * 100, 1)) : 0;
    $medalIcon = match((int)$rank) {
        1 => 'mdi:medal',
        2 => 'mdi:medal-outline',
        3 => 'mdi:medal-outline',
        default => 'mdi:circle-small',
    };
    $prizeAmount = data_get($prize, 'cash', 0);
@endphp

<div class="lb-rank-card lb-rank-card--filled lb-rank-{{ $rank }} {{ $isCurrentUser ? 'lb-rank-card--me' : '' }} {{ $completed ? 'lb-rank-card--done' : '' }}">

    {{-- Medal / position --}}
    <div class="lb-rank-pos lb-rank-pos--{{ $rank }}">
        <iconify-icon icon="{{ $medalIcon }}"></iconify-icon>
    </div>

    {{-- Avatar --}}
    <div class="lb-avatar">
        @if($avatar)
            <img src="{{ $avatar }}" alt="{{ $name }}" />
        @else
            <span>{{ strtoupper(substr($name, 0, 1)) }}</span>
        @endif

        @if($completed)
            <div class="lb-avatar-crown">
                <iconify-icon icon="mdi:crown"></iconify-icon>
            </div>
        @endif
    </div>

    {{-- Info --}}
    <div class="lb-rank-info">
        <span class="lb-rank-name">
            {{ $name }}
            @if($isCurrentUser)
                <span class="lb-you-tag">You</span>
            @endif
        </span>

        {{-- Mini progress bar --}}
        <div class="lb-rank-mini-bar">
            <div class="lb-rank-mini-fill lb-rank-fill--{{ $rank }}" style="width: {{ $pct }}%"></div>
        </div>

        <span class="lb-rank-sub">
            ${{ number_format($score, 0) }}
            <span class="lb-rank-sub-sep">/</span>
            ${{ number_format($target, 0) }} PV
            <span class="lb-rank-pct">({{ $pct }}%)</span>
        </span>
    </div>

    {{-- Right side: prize + movement --}}
    <div class="lb-rank-right">

        @if($prizeAmount > 0)
            <div class="lb-prize-tag lb-prize-tag--{{ $rank }}">
                ${{ number_format($prizeAmount) }}
            </div>
        @endif

        @if($rankChange > 0)
            <div class="lb-rank-move lb-rank-move--up">
                <iconify-icon icon="mdi:trending-up"></iconify-icon>
                +{{ $rankChange }}
            </div>
        @elseif($rankChange < 0)
            <div class="lb-rank-move lb-rank-move--down">
                <iconify-icon icon="mdi:trending-down"></iconify-icon>
                {{ $rankChange }}
            </div>
        @else
            <div class="lb-rank-move lb-rank-move--neutral">
                <iconify-icon icon="mdi:minus"></iconify-icon>
            </div>
        @endif

    </div>

    {{-- Qualified badge --}}
    @if($completed)
        <div class="lb-qualified-ribbon">
            <iconify-icon icon="mdi:check-circle"></iconify-icon>
            Qualified
        </div>
    @endif

</div>