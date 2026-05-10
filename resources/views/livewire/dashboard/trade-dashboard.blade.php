@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&family=JetBrains+Mono:wght@400;500;600;700&display=swap');

        :root {
            /* Premium Dark Palette */
            --bg-void: #0a0e17;
            --bg-canvas: #0f141f;
            --bg-elevated: #151b28;
            --bg-surface: #1a2030;
            --bg-hover: #1f2638;

            /* Sophisticated Borders */
            --border-subtle: rgba(99, 110, 123, 0.06);
            --border-default: rgba(99, 110, 123, 0.1);
            --border-strong: rgba(99, 110, 123, 0.18);
            --border-accent: rgba(56, 189, 248, 0.25);

            /* Premium Text Hierarchy */
            --text-hero: #ffffff;
            --text-primary: #e8edf4;
            --text-secondary: #9ba3af;
            --text-tertiary: #6b7280;
            --text-muted: #4b5563;

            /* Market Colors - Refined */
            --profit: #10b981;
            --profit-bg: rgba(16, 185, 129, 0.08);
            --profit-border: rgba(16, 185, 129, 0.2);
            --profit-glow: rgba(16, 185, 129, 0.2);

            --loss: #ef4444;
            --loss-bg: rgba(239, 68, 68, 0.08);
            --loss-border: rgba(239, 68, 68, 0.2);
            --loss-glow: rgba(239, 68, 68, 0.2);

            /* Premium Accents */
            --cyan: #06b6d4;
            --cyan-glow: rgba(6, 182, 212, 0.15);
            --amber: #f59e0b;
            --violet: #8b5cf6;

            /* Shadows */
            --shadow-sm: 0 1px 3px rgba(0, 0, 0, 0.3);
            --shadow-md: 0 4px 12px rgba(0, 0, 0, 0.4);
            --shadow-lg: 0 8px 24px rgba(0, 0, 0, 0.5);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg-void);
            overflow-x: hidden;
        }

        .dashboard {
            min-height: 100vh;
            background: var(--bg-void);
            padding: clamp(12px, 2.5vw, 32px);
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-primary);
        }

        /* Premium Header */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: clamp(20px, 3vw, 32px);
            padding-bottom: clamp(12px, 2vw, 20px);
            border-bottom: 1px solid var(--border-default);
            flex-wrap: wrap;
            gap: 12px;
        }

        .title-group {
            display: flex;
            flex-direction: column;
            gap: 6px;
        }

        .title {
            font-size: clamp(20px, 4vw, 32px);
            font-weight: 800;
            letter-spacing: -0.02em;
            background: linear-gradient(135deg, var(--text-hero) 0%, var(--cyan) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.2;
        }

        .subtitle {
            font-size: clamp(12px, 1.8vw, 14px);
            color: var(--text-tertiary);
            font-weight: 500;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .live-indicator {
            display: flex;
            align-items: center;
            gap: 8px;
            background: var(--bg-elevated);
            padding: 8px 14px;
            border-radius: 10px;
            border: 1px solid var(--border-strong);
            font-size: 13px;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
        }

        .pulse-dot {
            width: 8px;
            height: 8px;
            background: var(--profit);
            border-radius: 50%;
            animation: pulse 2s ease-in-out infinite;
            box-shadow: 0 0 12px var(--profit-glow);
        }

        @keyframes pulse {

            0%,
            100% {
                opacity: 1;
                transform: scale(1);
            }

            50% {
                opacity: 0.7;
                transform: scale(0.85);
            }
        }

        /* DESKTOP TABLE */
        .table-wrapper {
            background: var(--bg-canvas);
            border: 1px solid var(--border-default);
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow-lg);
        }

        .table-scroll {
            overflow-x: auto;
            overflow-y: visible;
        }

        .trade-table {
            width: 100%;
            min-width: 1000px;
            border-collapse: separate;
            border-spacing: 0;
            font-family: 'JetBrains Mono', monospace;
        }

        .trade-table thead {
            background: var(--bg-elevated);
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .trade-table th {
            padding: 14px 16px;
            text-align: left;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-tertiary);
            white-space: nowrap;
            border-bottom: 1px solid var(--border-strong);
            font-family: 'Inter', sans-serif;
        }

        .trade-table th:first-child {
            padding-left: 20px;
        }

        .trade-table th:last-child {
            padding-right: 20px;
        }

        .trade-table tbody tr {
            border-bottom: 1px solid var(--border-subtle);
            transition: all 0.2s ease;
            background: var(--bg-canvas);
        }

        .trade-table tbody tr:hover {
            background: var(--bg-elevated);
        }

        .trade-table tbody tr:last-child {
            border-bottom: none;
        }

        .trade-table td {
            padding: 16px;
            font-size: 13px;
            color: var(--text-primary);
            font-weight: 500;
            vertical-align: middle;
        }

        .trade-table td:first-child {
            padding-left: 20px;
        }

        .trade-table td:last-child {
            padding-right: 20px;
        }

        /* Asset Cell */
        .asset-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .asset-icon {
            width: 32px;
            height: 32px;
            background: linear-gradient(135deg, var(--cyan), var(--violet));
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 12px;
            font-weight: 700;
            flex-shrink: 0;
            color: white;
            letter-spacing: -0.02em;
        }

        .asset-name {
            font-weight: 700;
            font-size: 14px;
            color: var(--text-hero);
            letter-spacing: -0.01em;
        }

        /* Time */
        .time-cell {
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: 400;
        }

        /* Exchange Badges */
        .exchange-badge {
            display: inline-flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 6px;
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.06em;
            white-space: nowrap;
            font-family: 'Inter', sans-serif;
        }

        .badge-long {
            background: var(--profit-bg);
            color: var(--profit);
            border: 1px solid var(--profit-border);
        }

        .badge-short {
            background: var(--loss-bg);
            color: var(--loss);
            border: 1px solid var(--loss-border);
        }

        /* Funding Rate Badge */
        .funding-rate {
            font-size: 11px;
            color: var(--text-secondary);
            font-weight: 500;
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .funding-rate-value {
            font-weight: 600;
        }

        .funding-rate-value.positive {
            color: var(--profit);
        }

        .funding-rate-value.negative {
            color: var(--loss);
        }

        /* Value Cells */
        .value-cell {
            font-variant-numeric: tabular-nums;
            display: flex;
            align-items: center;
            gap: 2px;
            font-weight: 600;
        }

        .value-symbol {
            font-size: 10px;
            opacity: 0.6;
        }

        .green {
            color: var(--profit) !important;
        }

        .red {
            color: var(--loss) !important;
        }

        .neutral {
            color: var(--text-secondary);
        }

        /* Total Column */
        .total-cell {
            font-size: 15px;
            font-weight: 700;
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: var(--text-tertiary);
        }

        .empty-state-icon {
            font-size: 48px;
            margin-bottom: 16px;
            opacity: 0.3;
        }

        .empty-state-text {
            font-size: 14px;
            font-weight: 600;
        }

        /* Scrollbar */
        .table-scroll::-webkit-scrollbar {
            height: 8px;
        }

        .table-scroll::-webkit-scrollbar-track {
            background: var(--bg-void);
        }

        .table-scroll::-webkit-scrollbar-thumb {
            background: var(--border-strong);
            border-radius: 4px;
        }

        .table-scroll::-webkit-scrollbar-thumb:hover {
            background: var(--cyan);
        }

        /* Hide mobile structure on desktop */
        .mobile-header,
        .mobile-content {
            display: none;
        }

        /* ========================================
                   MOBILE LAYOUT - COINALYZE INSPIRED
                ======================================== */
        @media (max-width: 768px) {
            .dashboard {
                padding: 12px;
            }

            .dashboard-header {
                margin-bottom: 16px;
                padding-bottom: 12px;
            }

            .title {
                font-size: 22px;
            }

            .subtitle {
                font-size: 12px;
            }

            .live-indicator {
                padding: 6px 12px;
                font-size: 12px;
            }

            .table-wrapper {
                background: transparent;
                border: none;
                box-shadow: none;
                border-radius: 0;
            }

            .table-scroll {
                overflow-x: visible;
            }

            .trade-table {
                display: block;
                min-width: 0;
                width: 100%;
            }

            .trade-table thead {
                display: none;
            }

            .trade-table tbody {
                display: block;
            }

            .trade-table tbody tr {
                display: block;
                background: var(--bg-elevated);
                border: 1px solid var(--border-default);
                border-radius: 12px;
                padding: 0;
                margin-bottom: 12px;
                box-shadow: var(--shadow-md);
                overflow: hidden;
            }

            .trade-table tbody tr:hover {
                background: var(--bg-elevated);
            }

            .trade-table td {
                display: block;
                padding: 0;
                border: none;
            }

            /* Show mobile structure on mobile */
            .mobile-header,
            .mobile-content {
                display: block;
            }

            .mobile-header {
                display: flex;
            }

            /* Mobile Header Row - Asset + Time + Total */
            .mobile-header {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 14px 16px;
                border-bottom: 1px solid var(--border-subtle);
                background: var(--bg-surface);
            }

            .mobile-header .asset-cell {
                flex: 1;
                min-width: 0;
            }

            .mobile-header .asset-icon {
                width: 36px;
                height: 36px;
                font-size: 13px;
            }

            .mobile-header .asset-name {
                font-size: 16px;
            }

            .mobile-header-right {
                display: flex;
                flex-direction: column;
                align-items: flex-end;
                gap: 4px;
            }

            .mobile-time {
                font-size: 11px;
                color: var(--text-tertiary);
                font-weight: 500;
                font-family: 'JetBrains Mono', monospace;
            }

            .mobile-total {
                font-size: 18px;
                font-weight: 700;
                font-family: 'JetBrains Mono', monospace;
            }

            /* Mobile Content Grid */
            .mobile-content {
                padding: 16px;
            }

            /* Exchanges Row */
            .mobile-row {
                display: flex;
                justify-content: space-between;
                align-items: flex-start;
                margin-bottom: 16px;
                padding-bottom: 16px;
                border-bottom: 1px solid var(--border-subtle);
            }

            .mobile-row:last-child {
                margin-bottom: 0;
                padding-bottom: 0;
                border-bottom: none;
            }

            .mobile-column {
                flex: 1;
                display: flex;
                flex-direction: column;
                gap: 8px;
            }

            .mobile-divider {
                width: 1px;
                background: var(--border-subtle);
                margin: 0 16px;
                align-self: stretch;
            }

            .mobile-label {
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: var(--text-muted);
                font-family: 'Inter', sans-serif;
                margin-bottom: 2px;
            }

            .mobile-value {
                font-size: 13px;
                font-weight: 600;
                color: var(--text-primary);
                font-family: 'JetBrains Mono', monospace;
            }

            /* Exchange badges in mobile */
            .mobile-column .exchange-badge {
                width: fit-content;
            }

            /* Funding rates in mobile */
            .mobile-column .funding-rate {
                font-size: 11px;
            }

            /* PnL Grid - 3 columns */
            .mobile-pnl-grid {
                display: grid;
                grid-template-columns: repeat(3, 1fr);
                gap: 12px;
                padding: 16px;
                background: var(--bg-canvas);
                border-radius: 8px;
                margin-top: 16px;
            }

            .mobile-pnl-item {
                display: flex;
                flex-direction: column;
                gap: 6px;
                align-items: center;
                text-align: center;
            }

            .mobile-pnl-label {
                font-size: 9px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.08em;
                color: var(--text-muted);
                font-family: 'Inter', sans-serif;
            }

            .mobile-pnl-value {
                font-size: 14px;
                font-weight: 700;
                font-family: 'JetBrains Mono', monospace;
            }

            /* Hide desktop-only columns on mobile */
            .trade-table td:nth-child(2),
            .trade-table td:nth-child(3),
            .trade-table td:nth-child(4),
            .trade-table td:nth-child(5),
            .trade-table td:nth-child(6),
            .trade-table td:nth-child(7),
            .trade-table td:nth-child(8),
            .trade-table td:nth-child(9),
            .trade-table td:nth-child(10) {
                display: none;
            }

            /* Hide the standalone desktop asset cell (last one) on mobile */
            .trade-table td:first-child>.asset-cell:last-child {
                display: none !important;
            }

            /* Show mobile structure */
            .trade-table td:first-child {
                display: block;
                padding: 0;
            }
        }

        /* Tablet Adjustments */
        @media (min-width: 769px) and (max-width: 1024px) {
            .trade-table {
                min-width: 900px;
            }

            .trade-table th,
            .trade-table td {
                padding: 14px 12px;
                font-size: 12px;
            }
        }

        /* Small mobile */
        @media (max-width: 400px) {
            .dashboard {
                padding: 10px;
            }

            .mobile-header {
                padding: 12px;
            }

            .mobile-header .asset-icon {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }

            .mobile-header .asset-name {
                font-size: 15px;
            }

            .mobile-total {
                font-size: 16px;
            }

            .mobile-content {
                padding: 12px;
            }

            .mobile-pnl-grid {
                gap: 8px;
                padding: 12px;
            }

            .mobile-pnl-value {
                font-size: 13px;
            }
        }
    </style>

    <style>
        /* ========================================
                LIVE VALUE UPDATE ANIMATIONS
            ======================================== */

        .live-value {
            position: relative;
            transition:
                color 0.35s ease,
                transform 0.25s ease,
                opacity 0.25s ease;
            will-change: transform;

            backface-visibility: hidden;
            transform: translateZ(0);
        }

        /* Value increased */
        .flash-green {
            animation: flashGreen 2.8s cubic-bezier(0.22, 1, 0.36, 1);
        }

        /* Value decreased */
        .flash-red {
            animation: flashRed 2.8s cubic-bezier(0.22, 1, 0.36, 1);
        }

        /* Row updated */
        .row-updated {
            animation: rowPulse 1.2s ease;
        }

        /* Number pop */
        .value-pop {
            animation: valuePop 1.4s cubic-bezier(0.22, 1, 0.36, 1);
        }

        /* Glow effect */
        .glow-green {
            box-shadow:
                0 0 0px rgba(16, 185, 129, 0),
                0 0 18px rgba(16, 185, 129, 0.25);
        }

        .glow-red {
            box-shadow:
                0 0 0px rgba(239, 68, 68, 0),
                0 0 18px rgba(239, 68, 68, 0.25);
        }

        @keyframes flashGreen {

            0% {
                background: rgba(16, 185, 129, 0);
                transform: scale(1);
                box-shadow: 0 0 0 rgba(16, 185, 129, 0);
            }

            15% {
                background: rgba(16, 185, 129, 0.22);
                transform: scale(1.03);
                box-shadow: 0 0 24px rgba(16, 185, 129, 0.35);
            }

            55% {
                background: rgba(16, 185, 129, 0.12);
                transform: scale(1.015);
                box-shadow: 0 0 18px rgba(16, 185, 129, 0.22);
            }

            100% {
                background: rgba(16, 185, 129, 0);
                transform: scale(1);
                box-shadow: 0 0 0 rgba(16, 185, 129, 0);
            }
        }

        @keyframes flashRed {

            0% {
                background: rgba(239, 68, 68, 0);
                transform: scale(1);
                box-shadow: 0 0 0 rgba(239, 68, 68, 0);
            }

            15% {
                background: rgba(239, 68, 68, 0.22);
                transform: scale(1.03);
                box-shadow: 0 0 24px rgba(239, 68, 68, 0.35);
            }

            55% {
                background: rgba(239, 68, 68, 0.12);
                transform: scale(1.015);
                box-shadow: 0 0 18px rgba(239, 68, 68, 0.22);
            }

            100% {
                background: rgba(239, 68, 68, 0);
                transform: scale(1);
                box-shadow: 0 0 0 rgba(239, 68, 68, 0);
            }
        }

        @keyframes valuePop {

            0% {
                transform: scale(1);
            }

            35% {
                transform: scale(1.09);
            }

            100% {
                transform: scale(1);
            }
        }

        @keyframes rowPulse {
            0% {
                background: rgba(6, 182, 212, 0);
            }

            30% {
                background: rgba(6, 182, 212, 0.05);
            }

            100% {
                background: rgba(6, 182, 212, 0);
            }
        }
    </style>
@endpush

<div class="dashboard" wire:poll.1500ms="refreshTrades">
    <!-- Premium Header -->
    <div class="dashboard-header">
        <div class="title-group">
            <h2 class="title">Live Trading</h2>
            <p class="subtitle">Real-time arbitrage execution monitor</p>
        </div>
        <div class="header-actions">
            <div class="live-indicator">
                <span class="pulse-dot"></span>
                <span>Live Feed</span>
            </div>
        </div>
    </div>

    <!-- Responsive Table -->
    <div class="table-wrapper">
        <div class="table-scroll">
            <table class="trade-table">
                <thead>
                    <tr>
                        <th>Asset</th>
                        <th>Time</th>
                        <th>Long</th>
                        <th>Long Rate</th>
                        <th>Short</th>
                        <th>Short Rate</th>
                        <th>Price PnL</th>
                        <th>Funding</th>
                        <th>Fees</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($trades as $trade)
                        <tr id="trade-{{ $trade->id }}">
                            <!-- DESKTOP VIEW -->
                            <!-- Asset -->
                            <td>
                                <!-- Mobile Header Structure -->
                                <div class="mobile-header">
                                    <div class="asset-cell">
                                        <div class="asset-icon">
                                            {{ substr($trade->asset->symbol, 0, 2) }}
                                        </div>
                                        <span class="asset-name">{{ $trade->asset->symbol }}</span>
                                    </div>
                                    <div class="mobile-header-right">
                                        <span class="mobile-time">{{ $trade->opened_at->format('H:i:s') }}</span>
                                        <div class="mobile-total value-cell {{ $trade->total_net >= 0 ? 'green' : 'red' }}">
                                            <span class="value-symbol">$</span>
                                            <span>{{ number_format(abs($trade->total_net), 2) }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mobile Content Structure -->
                                <div class="mobile-content">
                                    <!-- Exchanges Row -->
                                    <div class="mobile-row">
                                        <div class="mobile-column">
                                            <div class="mobile-label">Long Exchange</div>
                                            <span class="exchange-badge badge-long">
                                                {{ $trade->long_exchange }}
                                            </span>
                                            <div class="funding-rate">
                                                <span>Rate:</span>
                                                <span
                                                    class="funding-rate-value {{ $trade->funding_rate_long >= 0 ? 'positive' : 'negative' }}">
                                                    {{ number_format($trade->funding_rate_long * 100, 4) }}%
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mobile-divider"></div>

                                        <div class="mobile-column">
                                            <div class="mobile-label">Short Exchange</div>
                                            <span class="exchange-badge badge-short">
                                                {{ $trade->short_exchange }}
                                            </span>
                                            <div class="funding-rate">
                                                <span>Rate:</span>
                                                <span
                                                    class="funding-rate-value {{ $trade->funding_rate_short >= 0 ? 'positive' : 'negative' }}">
                                                    {{ number_format($trade->funding_rate_short * 100, 4) }}%
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- PnL Grid -->
                                    <div class="mobile-pnl-grid">
                                        <div class="mobile-pnl-item">
                                            <div class="mobile-pnl-label">Price PnL</div>
                                            <div class="mobile-pnl-value {{ $trade->price_pnl >= 0 ? 'green' : 'red' }}">
                                                ${{ number_format(abs($trade->price_pnl), 2) }}
                                            </div>
                                        </div>
                                        <div class="mobile-pnl-item">
                                            <div class="mobile-pnl-label">Funding</div>
                                            <div
                                                class="mobile-pnl-value {{ $trade->funding_profit >= 0 ? 'green' : ($trade->funding_profit < 0 ? 'red' : 'neutral') }}">
                                                ${{ number_format(abs($trade->funding_profit), 2) }}
                                            </div>
                                        </div>
                                        <div class="mobile-pnl-item">
                                            <div class="mobile-pnl-label">Fees</div>
                                            <div class="mobile-pnl-value red">
                                                -${{ number_format($trade->fees, 2) }}
                                            </div>
                                        </div>
                                    </div>
                                </div>

                                <!-- Desktop Asset (hidden on mobile) -->
                                <div class="asset-cell">
                                    <div class="asset-icon">
                                        {{ substr($trade->asset->symbol, 0, 2) }}
                                    </div>
                                    <span class="asset-name">{{ $trade->asset->symbol }}</span>
                                </div>
                            </td>

                            <!-- Time (Desktop only) -->
                            <td class="time-cell">
                                {{ $trade->opened_at->format('H:i:s') }}
                            </td>

                            <!-- Long Exchange (Desktop only) -->
                            <td>
                                <span class="exchange-badge badge-long">
                                    {{ $trade->long_exchange }}
                                </span>
                            </td>

                            <!-- Long Funding Rate (Desktop only) -->
                            <td>
                                <div class="funding-rate">
                                    <span
                                        class="live-value funding-rate-value {{ $trade->long_funding_rate >= 0 ? 'positive' : 'negative' }}"
                                        data-value="{{ $trade->long_funding_rate }}" data-field="long_funding_rate">
                                        {{ number_format($trade->funding_rate_long * 100, 4) }}%
                                    </span>
                                </div>
                            </td>

                            <!-- Short Exchange (Desktop only) -->
                            <td>
                                <span class="exchange-badge badge-short">
                                    {{ $trade->short_exchange }}
                                </span>
                            </td>

                            <!-- Short Funding Rate (Desktop only) -->
                            <td>
                                <div class="funding-rate">
                                    <span
                                        class="live-value funding-rate-value {{ $trade->short_funding_rate >= 0 ? 'positive' : 'negative' }}"
                                        data-value="{{ $trade->short_funding_rate }}" data-field="short_funding_rate">
                                        {{ number_format($trade->funding_rate_short * 100, 4) }}%
                                    </span>
                                </div>
                            </td>

                            <!-- Price PnL (Desktop only) -->
                            <td class="">
                                <div class="value-cell">
                                    <span class="value-symbol {{ $trade->price_pnl >= 0 ? 'green' : 'red' }}">$</span>
                                    <span class="live-value {{ $trade->price_pnl >= 0 ? 'green' : 'red' }}"
                                        data-value="{{ $trade->price_pnl }}" data-field="price_pnl">
                                        {{ number_format(abs($trade->price_pnl), 2) }}
                                    </span>
                                </div>
                            </td>

                            <!-- Funding (Desktop only) -->
                            <td>
                                <div class="value-cell">
                                    <span
                                        class="value-symbol {{ $trade->funding_profit >= 0 ? 'green' : ($trade->funding_profit < 0 ? 'red' : 'neutral') }}">$</span>
                                    <span
                                        class="live-value {{ $trade->funding_profit >= 0 ? 'green' : ($trade->funding_profit < 0 ? 'red' : 'neutral') }}"
                                        data-value="{{ $trade->funding_profit }}" data-field="funding_profit">
                                        {{ number_format(abs($trade->funding_profit), 2) }}
                                    </span>
                                </div>
                            </td>

                            <!-- Fees (Desktop only) -->
                            <td class="red">
                                <div class="value-cell">
                                    <span class="value-symbol red">-$</span>
                                    <span class="red">{{ number_format($trade->fees, 2) }}</span>
                                </div>
                            </td>

                            <!-- Total Net (Desktop only) -->
                            <td class="total-cell {{ $trade->total_net >= 0 ? 'green' : 'red' }}">
                                <div class="value-cell">
                                    <span class="value-symbol">$</span>
                                    <span class="live-value {{ $trade->total_net >= 0 ? 'green' : 'red' }}"
                                        data-value="{{ $trade->total_net }}" data-field="total_net">
                                        {{ number_format(abs($trade->total_net), 2) }}
                                    </span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="10">
                                <div class="empty-state">
                                    <div class="empty-state-icon">📊</div>
                                    <div class="empty-state-text">No active trades</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

@push('scripts')
    <script>
        const previousValues = {};

        function animateValueChanges() {

            document.querySelectorAll('.live-value').forEach(el => {

                const current = parseFloat(el.dataset.value || 0);

                const rowId = el.closest('tr')?.id || '';

                const field = el.dataset.field || '';

                const key = rowId + '-' + field;

                // first load
                if (!(key in previousValues)) {
                    previousValues[key] = current;
                    return;
                }

                const previous = previousValues[key];

                if (current !== previous) {

                    el.classList.remove(
                        'flash-green',
                        'flash-red',
                        'value-pop',
                        'glow-green',
                        'glow-red'
                    );

                    void el.offsetWidth;

                    if (current > previous) {

                        el.classList.add(
                            'flash-green',
                            'value-pop',
                            'glow-green'
                        );

                    } else {

                        el.classList.add(
                            'flash-red',
                            'value-pop',
                            'glow-red'
                        );
                    }

                    const row = el.closest('tr');

                    if (row) {

                        row.classList.remove('row-updated');

                        void row.offsetWidth;

                        row.classList.add('row-updated');
                    }

                    previousValues[key] = current;
                }
            });
        }

        document.addEventListener('livewire:init', () => {

            Livewire.hook('morphed', () => {
                animateValueChanges();
            });

        });
    </script>
@endpush