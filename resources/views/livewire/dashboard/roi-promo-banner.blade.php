<div style="display: contents">
@if($promo)
    @php
        $mult = rtrim(rtrim(number_format((float) $promo->multiplier, 2), '0'), '.');
        $target = $live ? $promo->ends_at : $promo->starts_at;

        $terms = [['icon' => 'ri-robot-2-line', 'text' => $promo->botNames()]];
        if ($promo->min_amount) $terms[] = ['icon' => 'ri-wallet-3-line', 'text' => 'From $' . number_format($promo->min_amount)];
        if ($promo->boost_duration === 'days' && $promo->boost_days) $terms[] = ['icon' => 'ri-time-line', 'text' => $promo->boost_days . '-day boost'];
        else $terms[] = ['icon' => 'ri-time-line', 'text' => 'Boost runs to maturity'];
        if ($promo->require_new_license) $terms[] = ['icon' => 'ri-key-2-line', 'text' => 'New licenses only'];
        if ($promo->audience === 'new') $terms[] = ['icon' => 'ri-user-add-line', 'text' => 'New accounts only'];
        if ($promo->require_kyc) $terms[] = ['icon' => 'ri-shield-check-line', 'text' => 'KYC required'];
        if ($promo->max_per_user) $terms[] = ['icon' => 'ri-stack-line', 'text' => $promo->max_per_user . ' per user'];

        $showSpots = $live && $promo->show_spots && $spotsLeft !== null;
        $spotsPct = $showSpots ? min(100, round(($promo->max_entries - $spotsLeft) / max(1, $promo->max_entries) * 100)) : 0;
    @endphp

    <section class="rpb rpb--{{ $promo->banner_theme }} {{ $promo->dismissible ? 'rpb--closable' : '' }}"
        x-data="{
            end: {{ $target->getTimestamp() * 1000 }},
            d: '00', h: '00', m: '00', s: '00', done: false,
            tick() {
                const t = Math.max(0, this.end - Date.now());
                const p = n => String(n).padStart(2, '0');
                this.done = t === 0;
                this.d = p(Math.floor(t / 864e5));
                this.h = p(Math.floor(t / 36e5) % 24);
                this.m = p(Math.floor(t / 6e4) % 60);
                this.s = p(Math.floor(t / 1e3) % 60);
            }
        }"
        x-init="tick(); setInterval(() => tick(), 1000)">

        <div class="rpb-glow" aria-hidden="true"></div>
        <div class="rpb-grid" aria-hidden="true"></div>

        @if($promo->dismissible)
            <button type="button" class="rpb-close" wire:click="dismiss" aria-label="Hide promo">
                <i class="ri-close-line"></i>
            </button>
        @endif

        <div class="rpb-body">
            <div class="rpb-main">
                <div class="rpb-eyebrow">
                    @if($live)
                        <span class="rpb-status"><span class="rpb-dot"></span>Live</span>
                    @else
                        <span class="rpb-status rpb-status--soon"><i class="ri-calendar-event-line"></i>Coming soon</span>
                    @endif
                    <span class="rpb-kicker">ROI Boost · {{ $promo->name }}</span>
                </div>

                <h3 class="rpb-title">{{ $promo->headlineText() }}</h3>
                <p class="rpb-desc">{{ $promo->descriptionText() }}</p>

                <ul class="rpb-terms">
                    @foreach($terms as $term)
                        <li><i class="{{ $term['icon'] }}"></i>{{ $term['text'] }}</li>
                    @endforeach
                </ul>

                <div class="rpb-actions">
                    @if($live && ($spotsLeft === null || $spotsLeft > 0))
                        <a href="{{ $ctaUrl }}" class="rpb-cta">
                            {{ $ctaLabel }} <i class="ri-arrow-right-up-line"></i>
                        </a>
                    @elseif($live)
                        <span class="rpb-cta rpb-cta--off">All spots taken</span>
                    @endif

                    @if($myPositions > 0)
                        <div class="rpb-mine">
                            <i class="ri-checkbox-circle-fill"></i>
                            <span>
                                <strong>{{ $myPositions }} boosted {{ \Illuminate\Support\Str::plural('position', $myPositions) }}</strong>
                                @if($myExtra > 0)
                                    · <em>+${{ number_format($myExtra, 2) }}</em> extra earned
                                @else
                                    · first boosted payout on the way
                                @endif
                            </span>
                        </div>
                    @endif
                </div>
            </div>

            <div class="rpb-side">
                <div class="rpb-mult">
                    <span class="rpb-label">Return multiplier</span>
                    <span class="rpb-mult-value">{{ $mult }}<em>×</em></span>
                    <span class="rpb-mult-sub">the bot's normal ROI</span>
                </div>

                @if($promo->show_countdown)
                    <div class="rpb-clock">
                        <span class="rpb-label" x-text="done ? '{{ $live ? 'Promo closed' : 'Starting now' }}' : '{{ $live ? 'Ends in' : 'Starts in' }}'">{{ $live ? 'Ends in' : 'Starts in' }}</span>
                        <div class="rpb-units">
                            <div><b x-text="d">00</b><span>Days</span></div>
                            <div><b x-text="h">00</b><span>Hrs</span></div>
                            <div><b x-text="m">00</b><span>Min</span></div>
                            <div><b x-text="s">00</b><span>Sec</span></div>
                        </div>
                    </div>
                @endif

                @if($showSpots)
                    <div class="rpb-spots">
                        <div class="rpb-spots-row">
                            <span class="rpb-label">Boosted spots</span>
                            <span class="rpb-spots-left">{{ number_format($spotsLeft) }} left</span>
                        </div>
                        <div class="rpb-bar"><span style="width: {{ $spotsPct }}%"></span></div>
                    </div>
                @endif
            </div>
        </div>
    </section>

    <style>
        .rpb {
            --rp-a: #22c55e;
            --rp-b: #009A76;
            --rp-soft: rgba(34, 197, 94, 0.12);
            --rp-line: rgba(34, 197, 94, 0.28);
            position: relative;
            overflow: hidden;
            border-radius: 18px;
            border: 1px solid rgba(255, 255, 255, 0.06);
            background: linear-gradient(160deg, #0b1324 0%, #070d1a 55%, #050a14 100%);
            color: #e2e8f0;
            isolation: isolate;
        }
        .rpb--gold { --rp-a: #f5c96a; --rp-b: #b8862b; --rp-soft: rgba(245, 201, 106, 0.12); --rp-line: rgba(245, 201, 106, 0.3); }
        .rpb--violet { --rp-a: #a78bfa; --rp-b: #6366f1; --rp-soft: rgba(167, 139, 250, 0.13); --rp-line: rgba(167, 139, 250, 0.3); }

        .rpb::before {
            content: "";
            position: absolute;
            inset: 0 0 auto 0;
            height: 1px;
            background: linear-gradient(90deg, transparent, var(--rp-a), transparent);
            opacity: 0.7;
        }
        .rpb-glow {
            position: absolute;
            width: 520px; height: 520px;
            right: -160px; top: -260px;
            background: radial-gradient(circle, var(--rp-soft) 0%, transparent 65%);
            filter: blur(6px);
            z-index: -1;
        }
        .rpb-grid {
            position: absolute; inset: 0; z-index: -1;
            background-image:
                linear-gradient(rgba(255,255,255,0.025) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255,255,255,0.025) 1px, transparent 1px);
            background-size: 28px 28px;
            mask-image: linear-gradient(120deg, transparent 30%, #000 75%);
            -webkit-mask-image: linear-gradient(120deg, transparent 30%, #000 75%);
        }
        .rpb-close {
            position: absolute; top: 12px; right: 12px;
            width: 30px; height: 30px; border-radius: 9px;
            display: grid; place-items: center;
            color: #64748b; background: rgba(255,255,255,0.03);
            border: 1px solid rgba(255,255,255,0.06);
            transition: .2s ease; z-index: 2;
        }
        .rpb-close:hover { color: #e2e8f0; background: rgba(255,255,255,0.07); }

        .rpb-body {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 300px;
            gap: 28px;
            padding: 26px 28px;
        }
        .rpb--closable .rpb-body { padding-right: 58px; }
        .rpb-eyebrow { display: flex; align-items: center; gap: 10px; flex-wrap: wrap; margin-bottom: 14px; padding-right: 36px; }
        .rpb-status {
            display: inline-flex; align-items: center; gap: 7px;
            padding: 4px 10px; border-radius: 999px;
            font-size: 11px; font-weight: 600; letter-spacing: .08em; text-transform: uppercase;
            color: var(--rp-a); background: var(--rp-soft); border: 1px solid var(--rp-line);
        }
        .rpb-status--soon { color: #cbd5e1; background: rgba(255,255,255,0.04); border-color: rgba(255,255,255,0.08); }
        .rpb-dot { width: 7px; height: 7px; border-radius: 50%; background: var(--rp-a); box-shadow: 0 0 0 0 var(--rp-a); animation: rpbPulse 2s infinite; }
        @keyframes rpbPulse { 0% { box-shadow: 0 0 0 0 var(--rp-line); } 70% { box-shadow: 0 0 0 8px transparent; } 100% { box-shadow: 0 0 0 0 transparent; } }
        .rpb-kicker { font-size: 11px; letter-spacing: .12em; text-transform: uppercase; color: #64748b; font-weight: 500; }

        .rpb-title {
            margin: 0; color: #fff;
            font-size: clamp(20px, 2.3vw, 27px); line-height: 1.2;
            font-weight: 700; letter-spacing: -0.02em;
            max-width: 560px;
        }
        .rpb-desc { margin: 10px 0 0; font-size: 13.5px; line-height: 1.65; color: #94a3b8; max-width: 560px; }

        .rpb-terms { list-style: none; padding: 0; margin: 16px 0 0; display: flex; flex-wrap: wrap; gap: 8px; }
        .rpb-terms li {
            display: inline-flex; align-items: center; gap: 6px;
            font-size: 12px; color: #cbd5e1;
            padding: 6px 10px; border-radius: 9px;
            background: rgba(255,255,255,0.03); border: 1px solid rgba(255,255,255,0.06);
        }
        .rpb-terms i { color: var(--rp-a); font-size: 14px; }

        .rpb-actions { display: flex; align-items: center; gap: 14px; flex-wrap: wrap; margin-top: 20px; }
        .rpb-cta {
            display: inline-flex; align-items: center; gap: 8px;
            padding: 11px 18px; border-radius: 11px;
            font-size: 13px; font-weight: 600; color: #04120c;
            background: linear-gradient(135deg, var(--rp-a), var(--rp-b));
            box-shadow: 0 8px 24px -10px var(--rp-a);
            text-decoration: none; transition: transform .2s ease, opacity .2s ease;
        }
        .rpb--violet .rpb-cta { color: #fff; }
        .rpb-cta:hover { transform: translateY(-1px); opacity: .95; }
        .rpb-cta--off { background: rgba(255,255,255,0.05); color: #64748b; box-shadow: none; cursor: default; }
        .rpb-mine { display: inline-flex; align-items: center; gap: 8px; font-size: 12.5px; color: #94a3b8; }
        .rpb-mine i { color: var(--rp-a); font-size: 16px; }
        .rpb-mine strong { color: #e2e8f0; font-weight: 600; }
        .rpb-mine em { font-style: normal; color: var(--rp-a); font-weight: 600; font-variant-numeric: tabular-nums; }

        .rpb-side { display: flex; flex-direction: column; gap: 12px; }
        .rpb-label { font-size: 10.5px; letter-spacing: .12em; text-transform: uppercase; color: #64748b; font-weight: 600; }
        .rpb-mult, .rpb-clock, .rpb-spots {
            border-radius: 14px; padding: 14px 16px;
            background: rgba(2, 6, 23, 0.55);
            border: 1px solid rgba(255,255,255,0.06);
            backdrop-filter: blur(8px);
        }
        .rpb-mult { display: flex; flex-direction: column; gap: 2px; border-color: var(--rp-line); background: linear-gradient(160deg, var(--rp-soft), rgba(2,6,23,0.6) 70%); }
        .rpb-mult-value {
            font-size: 46px; line-height: 1.05; font-weight: 800; letter-spacing: -0.04em;
            background: linear-gradient(135deg, #fff 10%, var(--rp-a) 90%);
            -webkit-background-clip: text; background-clip: text; color: transparent;
            font-variant-numeric: tabular-nums;
        }
        .rpb-mult-value em { font-style: normal; font-size: 30px; margin-left: 2px; }
        .rpb-mult-sub { font-size: 12px; color: #94a3b8; }

        .rpb-units { display: grid; grid-template-columns: repeat(4, 1fr); gap: 6px; margin-top: 8px; }
        .rpb-units div { text-align: center; padding: 7px 0 6px; border-radius: 9px; background: rgba(255,255,255,0.03); }
        .rpb-units b { display: block; font-size: 18px; font-weight: 700; color: #fff; font-variant-numeric: tabular-nums; letter-spacing: -0.02em; }
        .rpb-units span { font-size: 9.5px; letter-spacing: .1em; text-transform: uppercase; color: #64748b; }

        .rpb-spots-row { display: flex; justify-content: space-between; align-items: center; }
        .rpb-spots-left { font-size: 12px; font-weight: 600; color: var(--rp-a); font-variant-numeric: tabular-nums; }
        .rpb-bar { margin-top: 9px; height: 6px; border-radius: 999px; background: rgba(255,255,255,0.06); overflow: hidden; }
        .rpb-bar span { display: block; height: 100%; border-radius: inherit; background: linear-gradient(90deg, var(--rp-b), var(--rp-a)); }

        @media (max-width: 1024px) {
            .rpb-body { grid-template-columns: minmax(0, 1fr) 260px; gap: 20px; }
        }
        @media (max-width: 768px) {
            .rpb-body, .rpb--closable .rpb-body { grid-template-columns: 1fr; padding: 20px 18px; gap: 18px; }
            .rpb-side { display: grid; grid-template-columns: auto 1fr; gap: 10px; }
            .rpb-spots { grid-column: 1 / -1; }
            .rpb-mult { justify-content: center; }
            .rpb-mult-value { font-size: 38px; }
            .rpb-mult-sub { display: none; }
            .rpb-eyebrow { margin-bottom: 10px; }
            .rpb-cta { width: 100%; justify-content: center; padding: 13px 18px; }
            .rpb-actions { gap: 12px; }
        }
        @media (max-width: 420px) {
            .rpb-mult, .rpb-clock, .rpb-spots { padding: 12px; }
            .rpb-mult-value { font-size: 34px; }
            .rpb-mult-value em { font-size: 22px; }
            .rpb-units { gap: 4px; }
            .rpb-units b { font-size: 15px; }
            .rpb-units span { font-size: 8.5px; }
        }
    </style>
@endif
</div>
