{{--
    resources/views/components/leaderboard/countdown.blade.php

    Props:
        string $endsAt  – ISO-8601 end datetime string
--}}

@props(['endsAt' => ''])

@php
    $id = 'lb-countdown-' . uniqid();
@endphp

<div class="lb-countdown" id="{{ $id }}" data-ends-at="{{ $endsAt }}">

    <div class="lb-countdown-title">Time Remaining</div>

    <div class="lb-countdown-grid">

        <div class="lb-countdown-unit">
            <span class="lb-countdown-val" data-unit="days">--</span>
            <span class="lb-countdown-lbl">Days</span>
        </div>

        <span class="lb-countdown-sep">:</span>

        <div class="lb-countdown-unit">
            <span class="lb-countdown-val" data-unit="hours">--</span>
            <span class="lb-countdown-lbl">Hrs</span>
        </div>

        <span class="lb-countdown-sep">:</span>

        <div class="lb-countdown-unit">
            <span class="lb-countdown-val" data-unit="minutes">--</span>
            <span class="lb-countdown-lbl">Min</span>
        </div>

        <span class="lb-countdown-sep">:</span>

        <div class="lb-countdown-unit">
            <span class="lb-countdown-val" data-unit="seconds">--</span>
            <span class="lb-countdown-lbl">Sec</span>
        </div>

    </div>

</div>

@once
    @push('scripts')
    <script>
    (function () {
        function initCountdown(el) {
            const endsAt = new Date(el.dataset.endsAt).getTime();

            function pad(n) { return String(n).padStart(2, '0'); }

            function tick() {
                const diff = endsAt - Date.now();

                if (diff <= 0) {
                    el.querySelectorAll('[data-unit]').forEach(u => u.textContent = '00');
                    el.classList.add('lb-countdown--expired');
                    return;
                }

                const days    = Math.floor(diff / 86400000);
                const hours   = Math.floor((diff % 86400000) / 3600000);
                const minutes = Math.floor((diff % 3600000) / 60000);
                const seconds = Math.floor((diff % 60000) / 1000);

                el.querySelector('[data-unit="days"]').textContent    = pad(days);
                el.querySelector('[data-unit="hours"]').textContent   = pad(hours);
                el.querySelector('[data-unit="minutes"]').textContent = pad(minutes);
                el.querySelector('[data-unit="seconds"]').textContent = pad(seconds);

                // Pulse the seconds digit
                const secEl = el.querySelector('[data-unit="seconds"]');
                secEl.classList.remove('lb-countdown-pulse');
                void secEl.offsetWidth; // reflow
                secEl.classList.add('lb-countdown-pulse');
            }

            tick();
            setInterval(tick, 1000);
        }

        // Init immediately and also after Livewire morphs the DOM
        function boot() {
            document.querySelectorAll('[id^="lb-countdown-"]').forEach(initCountdown);
        }

        document.addEventListener('DOMContentLoaded', boot);
        document.addEventListener('livewire:navigated', boot);
        document.addEventListener('livewire:update', boot);
    })();
    </script>
    @endpush
@endonce