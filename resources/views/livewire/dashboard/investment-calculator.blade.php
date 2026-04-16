@push('styles')
    <style>
        /* =========================
               INVORA CALCULATOR ELITE
            ========================= */

        :root {
            --invora-green: #22c55e;
            --invora-bg: #0b0f14;
            --invora-card: rgba(255, 255, 255, 0.03);
            --invora-border: rgba(255, 255, 255, 0.06);
        }

        /* GLOBAL SPACING FIX */
        .invora-invest-page {
            padding-bottom: 100px;
        }

        /* ================= HERO ================= */

        .invora-calc-hero {
            position: relative;
            border-radius: 24px;
            padding: 22px;
            background:
                radial-gradient(circle at 20% 0%, rgba(34, 197, 94, 0.25), transparent 40%),
                radial-gradient(circle at 100% 100%, rgba(34, 197, 94, 0.15), transparent 50%),
                #0b0f14;
            border: 1px solid rgba(255, 255, 255, 0.05);
            overflow: hidden;
        }

        .hero-content {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            flex-wrap: wrap;
        }

        .metric-value {
            font-size: 32px;
            font-weight: 700;
            letter-spacing: -0.5px;
        }

        .metric-profit {
            color: var(--invora-green);
            font-size: 14px;
        }

        /* ================= BOT GRID ================= */

        .bot-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .bot-card {
            padding: 14px;
            border-radius: 16px;
            background: var(--invora-card);
            border: 1px solid var(--invora-border);
            transition: all 0.25s ease;
            position: relative;
            overflow: hidden;
        }

        .bot-card::after {
            content: "";
            position: absolute;
            inset: 0;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.15), transparent 60%);
            opacity: 0;
            transition: 0.3s;
        }

        .bot-card:hover::after {
            opacity: 1;
        }

        .bot-card.active {
            border-color: var(--invora-green);
            box-shadow: 0 0 20px rgba(34, 197, 94, 0.2);
        }

        .bot-name {
            font-size: 14px;
            font-weight: 600;
        }

        .bot-meta {
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #9ca3af;
            margin-top: 6px;
        }

        /* ================= CAPITAL ================= */

        .capital-box {
            border-radius: 18px;
            padding: 18px;
            background: linear-gradient(145deg,
                    rgba(255, 255, 255, 0.04),
                    rgba(255, 255, 255, 0.01));
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .capital-input {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .capital-input span {
            font-size: 20px;
            color: var(--invora-green);
        }

        .capital-input input {
            font-size: 28px;
            font-weight: 600;
            background: transparent;
            border: none;
            color: #fff;
            width: 100%;
        }

        /* PRESETS (NOW LOOK BUTTON-LIKE) */

        .capital-presets {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 8px;
            margin-top: 14px;
        }

        .capital-presets button {
            padding: 10px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
            font-size: 12px;
            transition: 0.2s;
        }

        .capital-presets button:active {
            transform: scale(0.96);
            background: rgba(34, 197, 94, 0.2);
        }

        /* ================= SWITCH ================= */

        .switch-card {
            padding: 16px;
            border-radius: 16px;
            background: var(--invora-card);
            border: 1px solid var(--invora-border);
        }

        .switch-title {
            font-weight: 600;
        }

        .switch-sub {
            font-size: 12px;
            color: #9ca3af;
        }

        .switch-toggle {
            width: 50px;
            height: 26px;
            border-radius: 20px;
            background: #222;
            position: relative;
        }

        .switch-toggle.active {
            background: var(--invora-green);
        }

        .toggle-dot {
            width: 20px;
            height: 20px;
            background: white;
            position: absolute;
            top: 3px;
            left: 3px;
            border-radius: 50%;
            transition: 0.3s;
        }

        .switch-toggle.active .toggle-dot {
            transform: translateX(24px);
        }

        /* ================= RESULTS ================= */

        .result-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 10px;
        }

        .result-card {
            padding: 16px;
            border-radius: 16px;
            background: var(--invora-card);
            border: 1px solid var(--invora-border);
        }

        .result-card.profit {
            background: rgba(34, 197, 94, 0.1);
            border-color: rgba(34, 197, 94, 0.3);
        }

        .result-card h2 {
            font-size: 20px;
            margin-top: 4px;
        }

        /* ================= RISK ================= */

        .invora-risk-box {
            margin-top: 18px;
            padding: 18px;
            border-radius: 18px;
            background: linear-gradient(145deg,
                    rgba(239, 68, 68, 0.12),
                    rgba(239, 68, 68, 0.04));
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .risk-value {
            font-size: 24px;
            font-weight: 600;
        }

        /* ================= MOBILE MAGIC ================= */

        @media (max-width: 768px) {

            .metric-value {
                font-size: 26px;
            }

            .bot-grid {
                grid-template-columns: 1fr 1fr;
            }

            .capital-input input {
                font-size: 24px;
            }

            .result-grid {
                grid-template-columns: 1fr 1fr;
            }

            .capital-presets {
                grid-template-columns: repeat(2, 1fr);
            }

        }
    </style>
@endpush

@push('styles')
    <style>
        :root {
            --invora-green: #22c55e;
            --invora-bg: #0b0f14;
        }

        /* GLOBAL */
        .invora-invest-page {
            padding: 16px;
            padding-bottom: 120px;
        }

        /* HERO */
        .invora-calc-hero {
            border-radius: 22px;
            padding: 24px;
            background:
                radial-gradient(circle at 10% 0%, rgba(34, 197, 94, 0.2), transparent 40%),
                #0b0f14;
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        /* TEXT */
        .metric-value {
            font-size: 34px;
            font-weight: 700;
        }

        .metric-profit {
            color: var(--invora-green);
        }

        /* NOTE */
        .earnings-note {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 8px;
        }

        /* SECTIONS */
        .invora-calc-section {
            margin-top: 28px;
        }

        /* BOT GRID */
        .bot-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        /* BOT CARD */
        .bot-card {
            padding: 16px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);

            backdrop-filter: blur(10px);

            transition: all 0.25s ease;
            cursor: pointer;
        }

        .bot-card:hover {
            transform: translateY(-2px);
        }

        /* ACTIVE */
        .bot-card.active {
            border-color: var(--invora-green);
            background: rgba(34, 197, 94, 0.1);
            box-shadow: 0 0 25px rgba(34, 197, 94, 0.25);
        }

        /* CAPITAL */
        .capital-box {
            padding: 20px;
            border-radius: 18px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .capital-input input {
            font-size: 30px;
            font-weight: 700;
            background: transparent;
            border: none;
            color: white;
        }

        /* PRESETS */
        .capital-presets button {
            padding: 12px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        .capital-presets button:active {
            transform: scale(0.95);
        }

        /* ================= SWITCH ================= */

        .switch-card {
            padding: 18px;
            border-radius: 18px;
            background: var(--invora-card);
            border: 1px solid var(--invora-border);
            cursor: pointer;
        }

        /* FLEX CONTROL (IMPORTANT) */
        .switch-content {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        /* TEXT BLOCK */
        .switch-text {
            flex: 1;
            min-width: 0;
        }

        /* TITLE */
        .switch-title {
            font-weight: 600;
            font-size: 14px;
        }

        /* SUBTEXT */
        .switch-sub {
            font-size: 12px;
            color: #9ca3af;
            margin-top: 4px;
            line-height: 1.4;
        }

        /* EXTRA INFO */
        .compound-info {
            font-size: 11px;
            color: #6b7280;
            margin-top: 6px;
            line-height: 1.4;
        }

        /* TOGGLE */
        .switch-toggle {
            width: 50px;
            height: 26px;
            min-width: 50px; /* PREVENT SHRINK */
            border-radius: 20px;
            background: #222;
            position: relative;
            flex-shrink: 0;
        }

        .switch-toggle.active {
            background: var(--invora-green);
        }

        /* DOT */
        .toggle-dot {
            width: 20px;
            height: 20px;
            background: white;
            position: absolute;
            top: 3px;
            left: 3px;
            border-radius: 50%;
            transition: 0.3s;
        }

        .switch-toggle.active .toggle-dot {
            transform: translateX(24px);
        }

        /* ================= MOBILE FIX ================= */

        @media (max-width: 768px) {

            .switch-content {
                flex-direction: column;
                align-items: flex-start;
                gap: 12px;
            }

            .switch-toggle {
                align-self: flex-end;
            }

            .switch-title {
                font-size: 15px;
            }

            .switch-sub {
                font-size: 12px;
            }

            .compound-info {
                font-size: 11px;
            }
        }

        /* RESULTS */
        .result-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }

        .result-card {
            padding: 18px;
            border-radius: 16px;
            background: rgba(255, 255, 255, 0.03);
            border: 1px solid rgba(255, 255, 255, 0.06);
        }

        .result-card.profit {
            background: rgba(34, 197, 94, 0.1);
        }

        .result-card h2 {
            font-size: 22px;
        }

        /* RISK */
        .invora-risk-box {
            margin-top: 22px;
            padding: 20px;
            border-radius: 18px;
            background: rgba(239, 68, 68, 0.1);
        }

        /* MOBILE */
        @media (max-width: 768px) {
            .bot-grid {
                grid-template-columns: 1fr 1fr;
            }

            .result-grid {
                grid-template-columns: 1fr 1fr;
            }

            .capital-presets {
                grid-template-columns: repeat(2, 1fr);
            }
        }
    </style>
@endpush

<div>
    <div class="invora-invest-page" x-data="{ selected: @entangle('selectedBotId') }">

        <div class="invora-hero-card invora-calc-hero">

            <div class="hero-content">
                <div>
                    <div class="invora-hero-title">Investment Simulator</div>
                    <div class="invora-hero-sub">
                        Project your earnings with real-time market logic
                    </div>
                </div>

                <div class="hero-metric">
                    <div class="metric-label">Projected Value</div>
                    <div class="metric-value">
                        ${{ number_format($result['final'] ?? 0, 2) }}
                    </div>

                    <div class="metric-profit">
                        +${{ number_format($result['profit'] ?? 0, 2) }}
                    </div>
                </div>
            </div>

        </div>

        <div class="earnings-note">
            Earnings update every 6-hour cycle • ~4 payouts per day
        </div>

        <div style="font-size:12px; color:#9ca3af;">
            Est. earnings: ${{ number_format(($result['daily'] ?? 0) / 24, 4) }} / hour
        </div>

        <div class="invora-calc-section">

            <div class="section-title">Select Strategy</div>

            <div class="bot-grid">
                @foreach($bots as $bot)
                    <div class="bot-card" :class="selected === {{ $bot->id }} ? 'active' : ''"
                        @click="selected = {{ $bot->id }}" wire:click="$set('selectedBotId', {{ $bot->id }})">
                        <div class="bot-name">{{ $bot->name }}</div>

                        <div class="bot-meta">
                            <span>{{ $bot->daily_return_percent }}% daily</span>
                            <span>Every {{ $bot->payout_interval_hours }}h</span>
                        </div>

                        <div class="bot-roi">
                            ROI • {{ $bot->license_duration_days }} days
                        </div>
                    </div>
                @endforeach
            </div>

        </div>

        <div class="invora-calc-section">

            <div class="section-title">Investment Capital</div>

            <div class="capital-box">

                <div class="capital-input">
                    <span>$</span>
                    <input type="number" wire:model.live="amount" />
                </div>

                <div class="capital-presets">
                    @foreach([100, 500, 1000, 5000] as $preset)
                        <button wire:click="$set('amount', {{ $preset }})">
                            ${{ number_format($preset) }}
                        </button>
                    @endforeach
                </div>

            </div>

        </div>

        <div class="invora-calc-section">

            <div 
                class="switch-card" 
                wire:click="$toggle('compound')"
            >

                <div class="switch-content">

                    <div class="switch-text">
                        <div class="switch-title">Auto Compounding</div>

                        <div class="switch-sub">
                            Reinvests profit every <strong>6 hours (4 cycles/day)</strong>
                        </div>

                        <div class="compound-info">
                            Profit is added back to capital each cycle to increase future payouts.
                        </div>
                    </div>

                    <div class="switch-toggle {{ $compound ? 'active' : '' }}">
                        <div class="toggle-dot"></div>
                    </div>

                </div>

            </div>

        </div>

        <div class="invora-calc-section">

            <div class="section-title">Projection Summary</div>

            <div class="result-grid">

                <div class="result-card profit">
                    <span>Total Profit</span>
                    <h2>${{ number_format($result['profit'] ?? 0, 2) }}</h2>
                </div>

                <div class="result-card">
                    <span>Daily Income</span>
                    <h2>${{ number_format($result['daily'] ?? 0, 2) }}</h2>
                </div>

                <div class="result-card">
                    <span>Return Rate</span>
                    <h2>{{ number_format($result['roi'] ?? 0, 2) }}%</h2>
                </div>

                <div class="result-card">
                    <span>Maturity</span>
                    <h2>{{ $result['matures_at'] ?? '-' }}</h2>
                </div>

            </div>

        </div>

        <div class="invora-risk-box">

            <div class="risk-title">Early Exit Impact</div>

            <div class="risk-value">
                ${{ number_format($result['early_return'] ?? 0, 2) }}
            </div>

            <div class="risk-note">
                Includes penalty deduction if withdrawn early
            </div>

        </div>

        <!-- CHART -->
        <div class="invora-chart-card mt-4 hidden">
            <div id="calcChart"></div>
        </div>

    </div>

</div>