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
    </style>
@endpush

<div>
    <div class="invora-invest-page coming-soon">

        <!-- HERO -->
        <div class="coming-hero">

            <div class="coming-badge">
                🚀 Mobile App Launching Soon
            </div>

            <h1 class="coming-title">
                The Future of finance<br>is Going Mobile
            </h1>

            <p class="coming-sub">
                A powerful, intelligent trading experience is being built for you.
                Faster. Smarter. Fully automated.
            </p>

            <!-- PHONE MOCK -->
            <div class="phone-preview">
                <div class="phone-glow"></div>
                <div class="phone-body">
                    <div class="screen">
                        <div class="fake-chart"></div>
                    </div>
                </div>
            </div>

            <!-- CTA -->
            <button class="notify-btn hidden">
                Get Early Access
            </button>

            <div class="coming-note">
                Be among the first users when we launch.
            </div>

        </div>

    </div>

</div>