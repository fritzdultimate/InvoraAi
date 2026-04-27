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

        .coming-soon {
            display: flex;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 40px 20px;
        }

        /* HERO */
        .coming-hero {
            max-width: 420px;
        }

        /* BADGE */
        .coming-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12px;
            background: rgba(34, 197, 94, 0.15);
            color: #22c55e;
            margin-bottom: 16px;
        }

        /* TITLE */
        .coming-title {
            font-size: 26px;
            font-weight: 700;
            line-height: 1.3;
        }

        /* SUBTEXT */
        .coming-sub {
            font-size: 14px;
            color: #9ca3af;
            margin-top: 10px;
        }

        /* PHONE MOCK */
        .phone-preview {
            position: relative;
            margin: 40px auto;
            width: 200px;
            height: 400px;
        }

        /* GLOW */
        .phone-glow {
            position: absolute;
            inset: -40px;
            background: radial-gradient(circle, rgba(34, 197, 94, 0.3), transparent 70%);
            filter: blur(40px);
        }

        /* PHONE */
        .phone-body {
            width: 100%;
            height: 100%;
            border-radius: 28px;
            background: #111;
            border: 1px solid rgba(255, 255, 255, 0.08);
            padding: 10px;
        }

        /* SCREEN */
        .screen {
            width: 100%;
            height: 100%;
            border-radius: 20px;
            background: #0b0f14;
            overflow: hidden;
        }

        /* FAKE CHART ANIMATION */
        .fake-chart {
            height: 100%;
            background: linear-gradient(120deg,
                    transparent 0%,
                    rgba(34, 197, 94, 0.4) 50%,
                    transparent 100%);
            animation: scan 2s infinite;
        }

        @keyframes scan {
            0% {
                transform: translateX(-100%);
            }

            100% {
                transform: translateX(100%);
            }
        }

        /* BUTTON */
        .notify-btn {
            width: 100%;
            padding: 14px;
            border-radius: 14px;
            background: #22c55e;
            color: black;
            font-weight: 600;
            margin-top: 20px;
            transition: 0.2s;
        }

        .notify-btn:active {
            transform: scale(0.97);
        }

        /* NOTE */
        .coming-note {
            font-size: 12px;
            color: #6b7280;
            margin-top: 10px;
        }

        .app-preview {
            position: relative;
            margin-top: 40px;
            border-radius: 20px;
            overflow: hidden;
            border: 1px solid rgba(255, 255, 255, 0.08);
        }

        /* BLURRED BACKGROUND */
        .preview-blur {
            filter: blur(12px);
            opacity: 0.6;
            padding: 20px;
            background: #0b0f14;
        }

        /* HEADER */
        .preview-header {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .dot {
            width: 10px;
            height: 10px;
            background: #22c55e;
            border-radius: 50%;
        }

        .line {
            height: 10px;
            background: rgba(255, 255, 255, 0.1);
            border-radius: 6px;
        }

        .w-60 {
            width: 60%;
        }

        /* CHART */
        .preview-chart {
            height: 120px;
            margin-top: 20px;
            border-radius: 12px;
            background: linear-gradient(120deg,
                    transparent,
                    rgba(34, 197, 94, 0.3),
                    transparent);
            animation: move 3s infinite;
        }

        @keyframes move {
            0% {
                background-position: -200px;
            }

            100% {
                background-position: 200px;
            }
        }

        /* CARDS */
        .preview-cards {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-top: 20px;
        }

        .card {
            height: 60px;
            border-radius: 12px;
            background: rgba(255, 255, 255, 0.05);
        }

        /* OVERLAY */
        .preview-overlay {
            position: absolute;
            inset: 0;
            backdrop-filter: blur(8px);
            background: rgba(0, 0, 0, 0.5);

            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;

            text-align: center;
            padding: 20px;
        }

        /* LOCK */
        .lock-icon {
            font-size: 28px;
            margin-bottom: 10px;
        }

        /* OVERLAY TEXT */
        .overlay-title {
            font-weight: 600;
            font-size: 18px;
        }

        .overlay-sub {
            font-size: 13px;
            color: #9ca3af;
            margin-top: 6px;
        }
    </style>

    <style>
        .trade-container {
            margin-top: 40px;
            display: grid;
            gap: 18px;
        }

        /* CARD */
        .trade-card {
            background: linear-gradient(145deg, rgba(255, 255, 255, 0.04), rgba(255, 255, 255, 0.02));
            border: 1px solid rgba(255, 255, 255, 0.08);
            border-radius: 18px;
            padding: 16px;
            transition: 0.25s;
            position: relative;
            overflow: hidden;
        }

        .trade-card:hover {
            transform: translateY(-4px);
            border-color: rgba(34, 197, 94, 0.6);
            box-shadow: 0 10px 30px rgba(34, 197, 94, 0.15);
        }

        /* TOP */
        .trade-top {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .trade-top .left {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .protocol {
            font-size: 12px;
            color: #22c55e;
            font-weight: 600;
        }

        .badge {
            font-size: 10px;
            color: #22c55e;
            opacity: 0.7;
        }

        .tx-link {
            font-size: 12px;
            color: #9ca3af;
        }

        /* MAIN */
        .trade-main {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 14px;
            gap: 10px;
        }

        /* SIDES */
        .trade-side {
            flex: 1;
            border-radius: 14px;
            padding: 12px;
            text-align: center;
        }

        .trade-side.buy {
            background: rgba(34, 197, 94, 0.08);
            border: 1px solid rgba(34, 197, 94, 0.2);
        }

        .trade-side.sell {
            background: rgba(239, 68, 68, 0.08);
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .side-label {
            font-size: 10px;
            color: #9ca3af;
        }

        .side-amount {
            font-size: 18px;
            font-weight: 700;
        }

        .side-symbol {
            font-size: 13px;
            margin-top: 2px;
        }

        .side-usd {
            font-size: 11px;
            color: #6b7280;
        }

        /* CENTER */
        .trade-center {
            width: 40px;
            text-align: center;
        }

        .arrow {
            font-size: 18px;
            color: #22c55e;
            animation: pulse 1.5s infinite;
        }

        @keyframes pulse {
            0% {
                opacity: 0.4;
            }

            50% {
                opacity: 1;
            }

            100% {
                opacity: 0.4;
            }
        }

        /* FOOTER */
        .trade-bottom {
            margin-top: 12px;
            display: flex;
            justify-content: space-between;
            font-size: 11px;
            color: #6b7280;
        }

        .hash {
            font-family: monospace;
        }

        /* 📱 MOBILE FIX (THIS is what you lacked) */
        @media (max-width: 640px) {
            .trade-main {
                flex-direction: column;
            }

            .trade-center {
                transform: rotate(90deg);
            }

            .trade-side {
                width: 100%;
            }
        }
    </style>
@endpush

<div>
    <div class="invora-invest-page coming-soon">

        <div class="coming-hero">

            <!-- BADGE -->
            <div class="coming-badge">
                ⚡ Live Trading Coming Soon
            </div>

            <!-- TITLE -->
            <h1 class="coming-title">
                Real-Time Trading.<br>Unlocked Soon.
            </h1>

            <!-- SUBTEXT -->
            <p class="coming-sub">
                Experience ultra-fast execution, smart automation, and real-time market intelligence.
                The next level of trading is almost here.
            </p>

            <!-- BLURRED APP PREVIEW -->
            <div class="app-preview">

                <!-- BACKGROUND UI (BLURRED) -->
                <div class="preview-blur">

                    <!-- fake header -->
                    <div class="preview-header">
                        <div class="dot"></div>
                        <div class="line w-60"></div>
                    </div>

                    <!-- fake chart -->
                    <div class="preview-chart"></div>

                    <!-- fake cards -->
                    <div class="preview-cards">
                        <div class="card"></div>
                        <div class="card"></div>
                    </div>

                </div>

                <!-- GLASS OVERLAY -->
                <div class="preview-overlay">
                    <div class="lock-icon">🔒</div>

                    <div class="overlay-title">
                        Locked Preview
                    </div>

                    <div class="overlay-sub">
                        Live trading will be available soon.
                    </div>

                    @if (auth()->user()->waitlist()->exists())
                        <button class="notify-btn"
                            style="background:#16a34a; display: flex; gap: 4px; justify-content: center; align-items: center;">
                            <iconify-icon icon="mdi:check-circle"></iconify-icon>
                            <span style="color: black">You're on the List</span>
                        </button>
                    @else
                        <button class="notify-btn" wire:click="wait">
                            <span wire:loading>Joining...</span>
                            <span wire:loading.remove>Get Early Access</span>
                        </button>
                    @endif
                </div>

            </div>

            <div class="coming-note">
                Limited early access • Be first to experience live trading
            </div>

        </div>

    </div>

    <div class="trade-container">

        @foreach(\App\Models\LiveTrade::latest()->take(20)->get() as $trade)

            <div class="trade-card">

                <!-- TOP BAR -->
                <div class="trade-top">
                    <div class="left">
                        <span class="protocol">{{ strtoupper($trade->protocol) }}</span>
                        <span class="badge">● Live</span>
                    </div>

                    <a href="https://etherscan.io/tx/{{ $trade->tx_hash }}" target="_blank" class="tx-link">
                        View Tx ↗
                    </a>
                </div>

                <!-- MAIN FLOW -->
                <div class="trade-main">

                    <!-- BUY -->
                    <div class="trade-side buy">
                        <div class="side-label">BUY</div>
                        <div class="side-amount">{{ number_format($trade->buy_amount, 4) }}</div>
                        <div class="side-symbol">{{ $trade->buy_symbol }}</div>
                        <div class="side-usd">${{ number_format($trade->buy_price_usd, 2) }}</div>
                    </div>

                    <!-- CENTER -->
                    <div class="trade-center">
                        <div class="arrow">⇄</div>
                    </div>

                    <!-- SELL -->
                    <div class="trade-side sell">
                        <div class="side-label">SELL</div>
                        <div class="side-amount">{{ number_format($trade->sell_amount, 4) }}</div>
                        <div class="side-symbol">{{ $trade->sell_symbol }}</div>
                        <div class="side-usd">${{ number_format($trade->sell_price_usd, 2) }}</div>
                    </div>

                </div>

                <!-- FOOTER -->
                <div class="trade-bottom">
                    <span>{{ \Carbon\Carbon::parse($trade->block_time)->diffForHumans() }}</span>
                    <span class="hash">
                        {{ substr($trade->tx_hash, 0, 6) }}...{{ substr($trade->tx_hash, -4) }}
                    </span>
                </div>

            </div>

        @endforeach

    </div>

</div>