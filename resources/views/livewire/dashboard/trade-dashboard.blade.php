@push('styles')
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Hanken+Grotesk:wght@300;400;500;600;700;800&family=Space+Mono:wght@400;700&display=swap');

        :root {
            /* Premium Dark Palette */
            --bg-void: #050810;
            --bg-canvas: #0d1117;
            --bg-elevated: #161b22;
            --bg-surface: #1c2128;
            --bg-hover: #21262d;
            
            /* Sophisticated Borders */
            --border-subtle: rgba(99, 110, 123, 0.08);
            --border-default: rgba(99, 110, 123, 0.12);
            --border-strong: rgba(99, 110, 123, 0.2);
            --border-accent: rgba(56, 189, 248, 0.3);
            
            /* Premium Text Hierarchy */
            --text-hero: #ffffff;
            --text-primary: #e6edf3;
            --text-secondary: #8b949e;
            --text-tertiary: #636e7b;
            
            /* Market Colors - Refined */
            --profit: #3fb950;
            --profit-bg: rgba(63, 185, 80, 0.08);
            --profit-border: rgba(63, 185, 80, 0.2);
            --profit-glow: rgba(63, 185, 80, 0.25);
            
            --loss: #f85149;
            --loss-bg: rgba(248, 81, 73, 0.08);
            --loss-border: rgba(248, 81, 73, 0.2);
            --loss-glow: rgba(248, 81, 73, 0.25);
            
            /* Premium Accents */
            --cyan: #38bdf8;
            --cyan-glow: rgba(56, 189, 248, 0.2);
            --amber: #fbbf24;
            --amber-glow: rgba(251, 191, 36, 0.15);
            --violet: #a78bfa;
            --violet-glow: rgba(167, 139, 250, 0.15);
            
            /* Shadows */
            --shadow-sm: 0 2px 8px rgba(0, 0, 0, 0.4);
            --shadow-md: 0 4px 16px rgba(0, 0, 0, 0.5);
            --shadow-lg: 0 8px 32px rgba(0, 0, 0, 0.6);
            --shadow-glow: 0 0 24px rgba(56, 189, 248, 0.15);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: var(--bg-void);
        }

        .dashboard {
            min-height: 100vh;
            background: var(--bg-void);
            background-image: 
                radial-gradient(ellipse at 10% 0%, rgba(56, 189, 248, 0.04) 0%, transparent 40%),
                radial-gradient(ellipse at 90% 100%, rgba(167, 139, 250, 0.03) 0%, transparent 40%);
            padding: clamp(16px, 3vw, 40px);
            font-family: 'Hanken Grotesk', -apple-system, BlinkMacSystemFont, sans-serif;
            color: var(--text-primary);
            animation: fadeIn 0.6s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        /* Premium Header */
        .dashboard-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: clamp(24px, 4vw, 40px);
            padding-bottom: clamp(16px, 3vw, 24px);
            border-bottom: 1px solid var(--border-default);
            flex-wrap: wrap;
            gap: 16px;
        }

        .title-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
        }

        .title {
            font-size: clamp(24px, 5vw, 36px);
            font-weight: 800;
            letter-spacing: -0.03em;
            background: linear-gradient(135deg, var(--text-hero) 0%, var(--cyan) 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            line-height: 1.1;
        }

        .subtitle {
            font-size: clamp(13px, 2vw, 15px);
            color: var(--text-tertiary);
            font-weight: 500;
            letter-spacing: 0.02em;
        }

        .header-actions {
            display: flex;
            align-items: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .live-indicator {
            display: flex;
            align-items: center;
            gap: 10px;
            background: var(--bg-elevated);
            padding: 10px 18px;
            border-radius: 12px;
            border: 1px solid var(--border-strong);
            font-size: 14px;
            font-weight: 600;
            box-shadow: var(--shadow-sm);
            backdrop-filter: blur(12px);
        }

        .pulse-dot {
            width: 10px;
            height: 10px;
            background: var(--profit);
            border-radius: 50%;
            animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
            box-shadow: 0 0 16px var(--profit-glow), 0 0 4px var(--profit);
            position: relative;
        }

        .pulse-dot::before {
            content: '';
            position: absolute;
            inset: -4px;
            border-radius: 50%;
            background: var(--profit);
            opacity: 0;
            animation: pulseRing 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
        }

        @keyframes pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(0.9); opacity: 0.8; }
        }

        @keyframes pulseRing {
            0% { transform: scale(0.8); opacity: 0.6; }
            100% { transform: scale(1.4); opacity: 0; }
        }

        /* Desktop: Scrollable Table */
        .table-wrapper {
            background: var(--bg-canvas);
            border: 1px solid var(--border-default);
            border-radius: 16px;
            overflow: hidden;
            box-shadow: var(--shadow-lg), var(--shadow-glow);
            backdrop-filter: blur(20px);
        }

        .table-scroll {
            overflow-x: auto;
            overflow-y: visible;
        }

        .trade-table {
            width: 100%;
            min-width: 900px;
            border-collapse: separate;
            border-spacing: 0;
            font-family: 'Space Mono', monospace;
        }

        /* Premium Table Header */
        .trade-table thead {
            background: var(--bg-elevated);
            position: sticky;
            top: 0;
            z-index: 100;
            box-shadow: 0 1px 0 var(--border-strong);
        }

        .trade-table th {
            padding: 20px 16px;
            text-align: left;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.1em;
            color: var(--text-secondary);
            white-space: nowrap;
            background: var(--bg-elevated);
            border-bottom: 2px solid var(--border-strong);
            position: relative;
        }

        .trade-table th::after {
            content: '';
            position: absolute;
            bottom: -2px;
            left: 0;
            right: 0;
            height: 2px;
            background: linear-gradient(90deg, transparent, var(--cyan), transparent);
            opacity: 0;
            transition: opacity 0.3s ease;
        }

        .trade-table th:hover::after {
            opacity: 0.3;
        }

        /* Premium Table Rows */
        .trade-table tbody tr {
            border-bottom: 1px solid var(--border-subtle);
            transition: all 0.25s cubic-bezier(0.4, 0, 0.2, 1);
            animation: slideUp 0.5s cubic-bezier(0.16, 1, 0.3, 1) backwards;
            background: var(--bg-canvas);
        }

        @keyframes slideUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .trade-table tbody tr:nth-child(1) { animation-delay: 0.05s; }
        .trade-table tbody tr:nth-child(2) { animation-delay: 0.1s; }
        .trade-table tbody tr:nth-child(3) { animation-delay: 0.15s; }
        .trade-table tbody tr:nth-child(4) { animation-delay: 0.2s; }
        .trade-table tbody tr:nth-child(5) { animation-delay: 0.25s; }
        .trade-table tbody tr:nth-child(6) { animation-delay: 0.3s; }
        .trade-table tbody tr:nth-child(7) { animation-delay: 0.35s; }
        .trade-table tbody tr:nth-child(8) { animation-delay: 0.4s; }

        .trade-table tbody tr:hover {
            background: var(--bg-elevated);
            box-shadow: inset 0 0 0 1px var(--border-accent);
            transform: translateY(-1px);
        }

        .trade-table td {
            padding: 20px 16px;
            font-size: 14px;
            color: var(--text-primary);
            font-weight: 500;
            vertical-align: middle;
        }

        /* Asset Cell - Premium Design */
        .asset-cell {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .asset-icon {
            width: 36px;
            height: 36px;
            background: linear-gradient(135deg, var(--cyan), var(--violet));
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 13px;
            font-weight: 700;
            flex-shrink: 0;
            box-shadow: 0 4px 12px var(--cyan-glow);
            letter-spacing: -0.02em;
            color: var(--text-hero);
        }

        .asset-name {
            font-weight: 700;
            font-size: 15px;
            letter-spacing: -0.01em;
            color: var(--text-hero);
        }

        /* Time Column */
        .time-cell {
            color: var(--text-tertiary);
            font-size: 13px;
            font-weight: 400;
        }

        /* Exchange Badges - Premium */
        .exchange-badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 12px;
            border-radius: 8px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            white-space: nowrap;
            transition: all 0.2s ease;
        }

        .badge-long {
            background: var(--profit-bg);
            color: var(--profit);
            border: 1px solid var(--profit-border);
            box-shadow: 0 2px 8px var(--profit-glow);
        }

        .badge-long:hover {
            background: rgba(63, 185, 80, 0.12);
            border-color: var(--profit);
        }

        .badge-short {
            background: var(--loss-bg);
            color: var(--loss);
            border: 1px solid var(--loss-border);
            box-shadow: 0 2px 8px var(--loss-glow);
        }

        .badge-short:hover {
            background: rgba(248, 81, 73, 0.12);
            border-color: var(--loss);
        }

        /* Value Cells - Premium Typography */
        .value-cell {
            font-variant-numeric: tabular-nums;
            display: flex;
            align-items: center;
            gap: 2px;
            font-weight: 600;
        }

        .value-symbol {
            font-size: 11px;
            opacity: 0.5;
            font-weight: 500;
        }

        .green {
            color: var(--profit);
        }

        .red {
            color: var(--loss);
        }

        .neutral {
            color: var(--text-secondary);
        }

        /* Total Column - Hero Treatment */
        .total-cell {
            font-size: 16px;
            font-weight: 700;
            letter-spacing: -0.01em;
        }

        .total-cell.green {
            text-shadow: 0 0 20px var(--profit-glow);
        }

        .total-cell.red {
            text-shadow: 0 0 20px var(--loss-glow);
        }

        /* Empty State */
        .empty-state {
            text-align: center;
            padding: 80px 20px;
            color: var(--text-tertiary);
        }

        .empty-state-icon {
            font-size: 56px;
            margin-bottom: 20px;
            opacity: 0.2;
            filter: grayscale(1);
        }

        .empty-state-text {
            font-size: 16px;
            font-weight: 600;
        }

        /* Custom Scrollbar */
        .table-scroll::-webkit-scrollbar {
            height: 10px;
        }

        .table-scroll::-webkit-scrollbar-track {
            background: var(--bg-void);
            border-radius: 0 0 16px 16px;
        }

        .table-scroll::-webkit-scrollbar-thumb {
            background: var(--border-strong);
            border-radius: 5px;
            transition: background 0.2s ease;
        }

        .table-scroll::-webkit-scrollbar-thumb:hover {
            background: var(--cyan);
            box-shadow: 0 0 12px var(--cyan-glow);
        }

        /* Mobile: Card Layout */
        @media (max-width: 768px) {
            .table-wrapper {
                background: transparent;
                border: none;
                box-shadow: none;
            }

            .table-scroll {
                overflow-x: visible;
            }

            .trade-table {
                display: block;
                min-width: 0;
            }

            .trade-table thead {
                display: none;
            }

            .trade-table tbody {
                display: block;
            }

            .trade-table tbody tr {
                display: grid;
                grid-template-columns: 1fr 1fr;
                gap: 16px;
                background: var(--bg-elevated);
                border: 1px solid var(--border-default);
                border-radius: 16px;
                padding: 20px;
                margin-bottom: 16px;
                box-shadow: var(--shadow-md);
            }

            .trade-table tbody tr:hover {
                transform: translateY(0);
                box-shadow: var(--shadow-md), inset 0 0 0 1px var(--border-accent);
            }

            .trade-table td {
                display: flex;
                flex-direction: column;
                gap: 6px;
                padding: 0;
                grid-column: span 1;
            }

            .trade-table td::before {
                content: attr(data-label);
                font-size: 10px;
                font-weight: 700;
                text-transform: uppercase;
                letter-spacing: 0.1em;
                color: var(--text-tertiary);
                font-family: 'Hanken Grotesk', sans-serif;
            }

            /* Mobile: Asset spans full width */
            .trade-table td:nth-child(1) {
                grid-column: span 2;
            }

            /* Mobile: Total spans full width */
            .trade-table td:nth-child(8) {
                grid-column: span 2;
                padding-top: 16px;
                border-top: 1px solid var(--border-subtle);
            }

            .asset-cell {
                gap: 14px;
            }

            .asset-icon {
                width: 44px;
                height: 44px;
                font-size: 15px;
            }

            .asset-name {
                font-size: 18px;
            }

            .value-cell {
                font-size: 15px;
            }

            .total-cell {
                font-size: 20px;
            }

            .empty-state {
                padding: 60px 20px;
            }
        }

        /* Tablet Adjustments */
        @media (min-width: 769px) and (max-width: 1024px) {
            .trade-table {
                min-width: 800px;
            }

            .trade-table th,
            .trade-table td {
                padding: 16px 12px;
            }

            .asset-icon {
                width: 32px;
                height: 32px;
                font-size: 12px;
            }
        }

        /* Ultra-wide screens */
        @media (min-width: 1920px) {
            .dashboard {
                max-width: 1800px;
                margin: 0 auto;
            }

            .trade-table th,
            .trade-table td {
                padding: 24px 20px;
            }

            .trade-table th {
                font-size: 12px;
            }

            .trade-table td {
                font-size: 15px;
            }

            .total-cell {
                font-size: 18px;
            }
        }

        /* Small mobile */
        @media (max-width: 400px) {
            .dashboard {
                padding: 12px;
            }

            .dashboard-header {
                margin-bottom: 20px;
            }

            .title {
                font-size: 22px;
            }

            .live-indicator {
                padding: 8px 14px;
                font-size: 13px;
            }

            .trade-table tbody tr {
                padding: 16px;
                gap: 12px;
            }
        }

        /* Print Styles */
        @media print {
            .dashboard {
                background: white;
                color: black;
            }

            .live-indicator {
                display: none;
            }

            .trade-table tbody tr:hover {
                background: transparent;
            }
        }

        /* Dark mode enhancement */
        @media (prefers-color-scheme: dark) {
            .dashboard {
                color-scheme: dark;
            }
        }

        /* Reduced motion */
        @media (prefers-reduced-motion: reduce) {
            *,
            *::before,
            *::after {
                animation-duration: 0.01ms !important;
                animation-iteration-count: 1 !important;
                transition-duration: 0.01ms !important;
            }
        }
    </style>
@endpush

<div class="dashboard">
    <!-- Premium Header -->
    <div class="dashboard-header">
        <div class="title-group">
            <h2 class="title">Live Trading Simulation</h2>
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
                        <th>Short</th>
                        <th>Price PnL</th>
                        <th>Funding</th>
                        <th>Fees</th>
                        <th>Total</th>
                    </tr>
                </thead>

                <tbody>
                    @forelse($trades as $trade)
                        <tr>
                            <!-- Asset -->
                            <td data-label="Asset">
                                <div class="asset-cell">
                                    <div class="asset-icon">
                                        {{ substr($trade->asset->symbol, 0, 2) }}
                                    </div>
                                    <span class="asset-name">{{ $trade->asset->symbol }}</span>
                                </div>
                            </td>

                            <!-- Time -->
                            <td data-label="Time" class="time-cell">
                                {{ $trade->opened_at->format('H:i:s') }}
                            </td>

                            <!-- Long Exchange -->
                            <td data-label="Long">
                                <span class="exchange-badge badge-long">
                                    {{ $trade->long_exchange }}
                                </span>
                            </td>

                            <!-- Short Exchange -->
                            <td data-label="Short">
                                <span class="exchange-badge badge-short">
                                    {{ $trade->short_exchange }}
                                </span>
                            </td>

                            <!-- Price PnL -->
                            <td data-label="Price PnL" class="{{ $trade->price_pnl >= 0 ? 'green' : 'red' }}">
                                <div class="value-cell">
                                    <span class="value-symbol">$</span>
                                    <span>{{ number_format(abs($trade->price_pnl), 2) }}</span>
                                </div>
                            </td>

                            <!-- Funding -->
                            <td data-label="Funding" class="{{ $trade->funding_profit >= 0 ? 'green' : ($trade->funding_profit < 0 ? 'red' : 'neutral') }}">
                                <div class="value-cell">
                                    <span class="value-symbol">$</span>
                                    <span>{{ number_format(abs($trade->funding_profit), 2) }}</span>
                                </div>
                            </td>

                            <!-- Fees -->
                            <td data-label="Fees" class="red">
                                <div class="value-cell">
                                    <span class="value-symbol">-$</span>
                                    <span>{{ number_format($trade->fees, 2) }}</span>
                                </div>
                            </td>

                            <!-- Total Net -->
                            <td data-label="Total" class="total-cell {{ $trade->total_net >= 0 ? 'green' : 'red' }}">
                                <div class="value-cell">
                                    <span class="value-symbol">$</span>
                                    <span>{{ number_format(abs($trade->total_net), 2) }}</span>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8">
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