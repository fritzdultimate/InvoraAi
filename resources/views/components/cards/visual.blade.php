{{--
    A rendered payment card with an optional back side (click to flip).
    Official network logos are not drawn here: put the files issued by your card partner at
    public/assets/images/cards/visa.svg and mastercard.svg and they replace the text mark automatically.
--}}
@props([
    'network' => 'visa',
    'design' => 'obsidian',
    'name' => null,
    'last4' => null,
    'expiry' => null,
    'tilt' => true,
    'flip' => false,
    'size' => 'md',
    'live' => null,
])
@php
    $network = $network instanceof \App\Enums\CardNetwork ? $network : \App\Enums\CardNetwork::from($network);
    $logo = file_exists(public_path("assets/images/cards/{$network->value}.svg")) ? asset("assets/images/cards/{$network->value}.svg") : null;
@endphp
<div {{ $attributes->class(['ic-card-wrap', 'ic-card-wrap--'.$size, 'is-flippable' => $flip]) }}
     x-data="{ rx: 0, ry: 0, flipped: false }"
     @if ($tilt)
        x-on:mousemove="const r = $el.getBoundingClientRect(); ry = ((event.clientX - r.left) / r.width - .5) * 14; rx = -((event.clientY - r.top) / r.height - .5) * 14"
        x-on:mouseleave="rx = 0; ry = 0"
     @endif
     @if ($flip) x-on:click="flipped = !flipped" role="button" tabindex="0" x-on:keydown.enter="flipped = !flipped" aria-label="Flip card" @endif>
    <div class="ic-card-3d" :style="`transform: rotateX(${rx}deg) rotateY(${ry + (flipped ? 180 : 0)}deg)`">

        {{-- front --}}
        <div class="ic-card ic-card--{{ $design }}">
            <span class="ic-card__sheen" aria-hidden="true"></span>
            <span class="ic-card__pattern" aria-hidden="true"></span>

            <div class="ic-card__top">
                <span class="ic-card__brand">INVORA</span>
                <svg class="ic-card__nfc" viewBox="0 0 24 24" aria-hidden="true">
                    <path d="M8 7.5a6.5 6.5 0 0 1 0 9M11.5 5a10 10 0 0 1 0 14M15 2.5a13.5 13.5 0 0 1 0 19" fill="none" stroke="currentColor" stroke-width="1.6" stroke-linecap="round"/>
                </svg>
            </div>

            <div class="ic-card__mid">
                <svg class="ic-card__chip" viewBox="0 0 46 36" aria-hidden="true">
                    <rect x="1" y="1" width="44" height="34" rx="7" fill="url(#icChip)" stroke="rgba(0,0,0,.25)"/>
                    <path d="M1 12h13M1 24h13M32 12h13M32 24h13M14 1v34M32 1v34M14 18h18" stroke="rgba(60,40,0,.45)" stroke-width="1.1" fill="none"/>
                    <defs><linearGradient id="icChip" x1="0" y1="0" x2="1" y2="1"><stop offset="0" stop-color="#f6e7b0"/><stop offset=".5" stop-color="#c9a24a"/><stop offset="1" stop-color="#f1d98b"/></linearGradient></defs>
                </svg>
            </div>

            <p class="ic-card__number">•••• &nbsp;•••• &nbsp;•••• &nbsp;<span>{{ $last4 ?: '••••' }}</span></p>

            <div class="ic-card__bottom">
                <div class="ic-card__holder">
                    <small>Card holder</small>
                    @if ($live)
                        <span x-text="({{ $live }}) || 'YOUR NAME'">{{ $name ?: 'YOUR NAME' }}</span>
                    @else
                        <span>{{ $name ?: 'YOUR NAME' }}</span>
                    @endif
                </div>
                <div class="ic-card__exp">
                    <small>Valid thru</small>
                    <span>{{ $expiry ?: '••/••' }}</span>
                </div>
                <div class="ic-card__network">
                    @if ($logo)
                        <img src="{{ $logo }}" alt="{{ $network->label() }}">
                    @else
                        <span class="ic-card__network-text ic-card__network-text--{{ $network->value }}">{{ $network->label() }}</span>
                    @endif
                </div>
            </div>
        </div>

        {{-- back --}}
        @if ($flip)
            <div class="ic-card ic-card--{{ $design }} ic-card--back" aria-hidden="true">
                <span class="ic-card__stripe"></span>
                <div class="ic-card__sign">
                    <span>Authorized signature</span>
                    <b>•••</b>
                </div>
                <p class="ic-card__fine">This card remains the property of the issuer. Use is subject to the cardholder agreement. If found, please return to Invora.</p>
                <div class="ic-card__back-foot">
                    <span class="ic-card__brand">INVORA</span>
                    <span class="ic-card__network-text ic-card__network-text--{{ $network->value }}">{{ $network->label() }}</span>
                </div>
            </div>
        @endif
    </div>
</div>
