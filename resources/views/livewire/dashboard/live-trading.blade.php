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
            background: rgba(34,197,94,0.15);
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
            background: radial-gradient(circle, rgba(34,197,94,0.3), transparent 70%);
            filter: blur(40px);
        }

        /* PHONE */
        .phone-body {
            width: 100%;
            height: 100%;
            border-radius: 28px;
            background: #111;
            border: 1px solid rgba(255,255,255,0.08);
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
            background: linear-gradient(
                120deg,
                transparent 0%,
                rgba(34,197,94,0.4) 50%,
                transparent 100%
            );
            animation: scan 2s infinite;
        }

        @keyframes scan {
            0% { transform: translateX(-100%); }
            100% { transform: translateX(100%); }
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
            border: 1px solid rgba(255,255,255,0.08);
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
            background: rgba(255,255,255,0.1);
            border-radius: 6px;
        }

        .w-60 { width: 60%; }

        /* CHART */
        .preview-chart {
            height: 120px;
            margin-top: 20px;
            border-radius: 12px;
            background: linear-gradient(
                120deg,
                transparent,
                rgba(34,197,94,0.3),
                transparent
            );
            animation: move 3s infinite;
        }

        @keyframes move {
            0% { background-position: -200px; }
            100% { background-position: 200px; }
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
            background: rgba(255,255,255,0.05);
        }

        /* OVERLAY */
        .preview-overlay {
            position: absolute;
            inset: 0;
            backdrop-filter: blur(8px);
            background: rgba(0,0,0,0.5);

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
                    Live trading will be available in the mobile app.
                </div>

                <button class="notify-btn hidden">
                    Get Early Access
                </button>
            </div>

        </div>

        <div class="coming-note">
            Limited early access • Be first to experience live trading
        </div>

    </div>

</div>

</div>