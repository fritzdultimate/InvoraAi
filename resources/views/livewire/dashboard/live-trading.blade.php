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
@endpush

@push('styles')
    <style>
    .dex-table-wrapper {
        margin-top: 40px;
        background: rgba(255,255,255,0.02);
        border: 1px solid rgba(255,255,255,0.06);
        border-radius: 18px;
        overflow: hidden;
    }

    .dex-toolbar {
        display: flex;
        justify-content: space-between;
        align-items: center;
        gap: 12px;
        flex-wrap: wrap;
        padding: 16px 18px;
        border-bottom: 1px solid rgba(255,255,255,0.06);
    }

    .dex-toolbar-left, .dex-toolbar-right {
        display: flex;
        align-items: center;
        gap: 10px;
        flex-wrap: wrap;
    }

    .dex-pills {
        display: flex;
        gap: 6px;
        background: rgba(255,255,255,0.03);
        padding: 4px;
        border-radius: 10px;
    }

    .dex-pill {
        padding: 6px 12px;
        font-size: 12px;
        color: #9ca3af;
        border-radius: 8px;
        background: transparent;
        border: none;
    }

    .dex-pill.active {
        background: #22c55e;
        color: #06210f;
        font-weight: 600;
    }

    .dex-select, .dex-search {
        background: rgba(255,255,255,0.04);
        border: 1px solid rgba(255,255,255,0.08);
        color: #e5e7eb;
        border-radius: 10px;
        padding: 8px 12px;
        font-size: 13px;
    }

    .dex-search { min-width: 220px; }

    .dex-live-badge {
        display: flex;
        align-items: center;
        gap: 6px;
        font-size: 12px;
        color: #22c55e;
    }

    .dex-live-dot {
        width: 8px;
        height: 8px;
        border-radius: 50%;
        background: #22c55e;
        animation: pulse 1.5s infinite;
    }

    .dex-table-scroll {
        overflow-x: auto;
    }

    .dex-table {
        width: 100%;
        border-collapse: collapse;
        font-size: 13px;
        white-space: nowrap;
    }

    .dex-table thead th {
        text-align: left;
        padding: 12px 16px;
        font-size: 11px;
        text-transform: uppercase;
        letter-spacing: 0.04em;
        color: #6b7280;
        background: rgba(255,255,255,0.02);
        position: sticky;
        top: 0;
    }

    .dex-table thead th.num { text-align: right; }

    .dex-table tbody td {
        padding: 12px 16px;
        border-top: 1px solid rgba(255,255,255,0.05);
        color: #e5e7eb;
    }

    .dex-table tbody tr:hover {
        background: rgba(34,197,94,0.04);
    }

    .dex-table td.num { text-align: right; font-variant-numeric: tabular-nums; }
    .dex-table td.value { color: #22c55e; font-weight: 600; }
    .dex-table td.muted { color: #9ca3af; }
    .dex-table td.pair { font-weight: 600; }

    .net-badge {
        font-size: 10px;
        padding: 3px 8px;
        border-radius: 6px;
        font-weight: 600;
    }
    .net-eth { background: rgba(99,102,241,0.15); color: #a5b4fc; }
    .net-bsc { background: rgba(245,158,11,0.15); color: #fcd34d; }
    .net-arbitrum { background: rgba(56,189,248,0.15); color: #7dd3fc; }

    .side-badge {
        font-size: 11px;
        padding: 4px 10px;
        border-radius: 999px;
        font-weight: 700;
    }
    .side-buy { background: rgba(34,197,94,0.15); color: #4ade80; }
    .side-sell { background: rgba(239,68,68,0.15); color: #f87171; }

    .tx-btn {
        font-size: 12px;
        color: #9ca3af;
        border: 1px solid rgba(255,255,255,0.08);
        padding: 5px 10px;
        border-radius: 8px;
    }
    .tx-btn:hover { color: #22c55e; border-color: rgba(34,197,94,0.4); }
    .tx-arrow { margin-left: 2px; }

    .empty-row {
        text-align: center;
        padding: 40px;
        color: #6b7280;
    }

    .dex-pagination {
        padding: 14px 18px;
    }

    @keyframes pulse {
        0% { opacity: 0.3; }
        50% { opacity: 1; }
        100% { opacity: 0.3; }
    }

    @media (max-width: 768px) {
        .dex-toolbar { flex-direction: column; align-items: stretch; }
        .dex-search { width: 100%; }
    }
    .unit {
        color: #6b7280;
        font-size: 11px;
        margin-left: 4px;
        font-weight: 500;
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

    <div class="dex-table-wrapper">

        <div class="dex-toolbar">
            <div class="dex-toolbar-left">
                <div class="dex-pills">
                    <button wire:click="$set('network', 'all')" class="dex-pill {{ $network === 'all' ? 'active' : '' }}">All</button>
                    <button wire:click="$set('network', 'eth')" class="dex-pill {{ $network === 'eth' ? 'active' : '' }}">Ethereum</button>
                    <button wire:click="$set('network', 'bsc')" class="dex-pill {{ $network === 'bsc' ? 'active' : '' }}">BSC</button>
                    <button wire:click="$set('network', 'arbitrum')" class="dex-pill {{ $network === 'arbitrum' ? 'active' : '' }}">Arbitrum</button>
                </div>

                <select wire:model.live="dex" class="dex-select">
                    <option value="all">All DEXs</option>
                    @foreach($dexOptions as $option)
                        <option value="{{ $option }}">{{ $option }}</option>
                    @endforeach
                </select>
            </div>

            <div class="dex-toolbar-right">
                <div class="dex-live-badge">
                    <span class="dex-live-dot"></span> Live
                </div>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Search pair or tx hash..." class="dex-search">
            </div>
        </div>

        <div class="dex-table-scroll" wire:poll.5s>
            <table class="dex-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Network</th>
                        <th>DEX</th>
                        <th>Pair</th>
                        <th>Side</th>
                        <th class="num">Price</th>
                        <th class="num">Amount</th>
                        <th class="num">Value (USD)</th>
                        <th>Tx</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trades as $trade)
                        <tr wire:key="trade-{{ $trade->id }}">
                            <td class="muted">{{ $trade->block_time?->diffForHumans(null, true) }} ago</td>
                            <td><span class="net-badge net-{{ $trade->network }}">{{ strtoupper($trade->network) }}</span></td>
                            <td class="muted">{{ $trade->dex }}</td>
                            <td class="pair">{{ $trade->pair }}</td>
                            <td>
                                <span class="side-badge side-{{ $trade->side }}">{{ strtoupper($trade->side) }}</span>
                            </td>
                            <td class="num">${{ rtrim(rtrim(number_format($trade->price, 6), '0'), '.') }}</td>
                            <td class="num">
                                {{ number_format($trade->amount, 4) }}
                                <span class="unit">{{ $trade->base_symbol }}</span>
                            </td>
                            <td class="num value">
                                ${{ number_format($trade->amount_usd, 2) }}
                            </td>
                            <td>
                                <a href="{{ $trade->explorer_url }}" target="_blank" rel="noopener" class="tx-btn">
                                    {{ $trade->explorer_label }} <span class="tx-arrow">↗</span>
                                </a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="9" class="empty-row">No trades match your filters yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        <div class="dex-pagination">
            {{ $trades->links() }}
        </div>

    </div>

</div>
