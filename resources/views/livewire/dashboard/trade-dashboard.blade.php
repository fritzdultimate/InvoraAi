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
            margin-bottom: clamp(20px, 3vw, 24px);
            padding-bottom: clamp(12px, 2vw, 16px);
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

        /* ========================================
               PREMIUM FILTER BAR
            ======================================== */
        .filter-bar {
            background: var(--bg-elevated);
            border: 1px solid var(--border-default);
            border-radius: 12px;
            padding: 16px;
            margin-bottom: 20px;
            box-shadow: var(--shadow-md);
            display: flex;
            gap: 16px;
            align-items: end;
            flex-wrap: wrap;
        }

        .filter-group {
            display: flex;
            flex-direction: column;
            gap: 8px;
            flex: 1;
            min-width: 180px;
        }

        .filter-label {
            font-size: 10px;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.08em;
            color: var(--text-muted);
        }

        /* Segmented Control (Status Filter) */
        .segmented-control {
            display: flex;
            background: var(--bg-canvas);
            border: 1px solid var(--border-default);
            border-radius: 8px;
            padding: 3px;
            gap: 3px;
        }

        .segment-option {
            flex: 1;
            padding: 8px 12px;
            font-size: 12px;
            font-weight: 600;
            text-align: center;
            border-radius: 6px;
            cursor: pointer;
            transition: all 0.2s ease;
            color: var(--text-secondary);
            background: transparent;
            border: none;
            white-space: nowrap;
        }

        .segment-option:hover {
            color: var(--text-primary);
            background: var(--bg-hover);
        }

        .segment-option.active {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.15), rgba(6, 182, 212, 0.08));
            color: var(--cyan);
            border: 1px solid rgba(6, 182, 212, 0.3);
            box-shadow: 0 2px 12px rgba(6, 182, 212, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        .segment-option.profit {
            color: var(--profit);
        }

        .segment-option.profit.active {
            background: linear-gradient(135deg, rgba(16, 185, 129, 0.15), rgba(16, 185, 129, 0.08));
            color: var(--profit);
            border: 1px solid rgba(16, 185, 129, 0.3);
            box-shadow: 0 2px 12px rgba(16, 185, 129, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        .segment-option.loss {
            color: var(--loss);
        }

        .segment-option.loss.active {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.15), rgba(239, 68, 68, 0.08));
            color: var(--loss);
            border: 1px solid rgba(239, 68, 68, 0.3);
            box-shadow: 0 2px 12px rgba(239, 68, 68, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.05);
        }

        /* Premium Select Dropdown */
        .custom-select {
            position: relative;
            width: 100%;
        }

        .custom-select select {
            width: 100%;
            padding: 10px 40px 10px 14px;
            font-size: 13px;
            font-weight: 500;
            color: var(--text-primary);
            background: var(--bg-canvas);
            border: 1px solid var(--border-default);
            border-radius: 8px;
            cursor: pointer;
            appearance: none;
            transition: all 0.2s ease;
            font-family: 'Inter', sans-serif;
        }

        .custom-select select:hover {
            border-color: var(--border-strong);
            background: var(--bg-surface);
        }

        .custom-select select:focus {
            outline: none;
            border-color: var(--cyan);
            box-shadow: 0 0 0 3px var(--cyan-glow);
        }

        .custom-select::after {
            content: '▼';
            position: absolute;
            right: 14px;
            top: 50%;
            transform: translateY(-50%);
            font-size: 10px;
            color: var(--text-tertiary);
            pointer-events: none;
        }

        /* Filter Stats Badge */
        .filter-stats {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 16px;
            background: var(--bg-canvas);
            border: 1px solid var(--border-default);
            border-radius: 8px;
            font-size: 12px;
            font-weight: 600;
            white-space: nowrap;
        }

        .filter-stats-label {
            color: var(--text-tertiary);
            font-weight: 500;
        }

        .filter-stats-value {
            color: var(--cyan);
            font-weight: 700;
            font-size: 14px;
        }

        /* Clear Filters Button */
        .clear-filters-btn {
            padding: 10px 18px;
            background: transparent;
            border: 1px solid var(--border-default);
            border-radius: 8px;
            color: var(--text-secondary);
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s ease;
            white-space: nowrap;
        }

        .clear-filters-btn:hover {
            background: var(--bg-hover);
            border-color: var(--border-strong);
            color: var(--text-primary);
        }

        .clear-filters-btn:active {
            transform: scale(0.98);
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

        /* Status Badge */
        .status-badge {
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

        .status-open {
            background: rgba(6, 182, 212, 0.1);
            color: var(--cyan);
            border: 1px solid rgba(6, 182, 212, 0.2);
        }

        .status-closed {
            background: rgba(107, 114, 128, 0.1);
            color: var(--text-tertiary);
            border: 1px solid rgba(107, 114, 128, 0.2);
        }

        .status-profit {
            background: var(--profit-bg);
            color: var(--profit);
            border: 1px solid var(--profit-border);
        }

        .status-loss {
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
               MOBILE LAYOUT
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

            /* Mobile Filter Bar */
            .filter-bar {
                padding: 12px;
                gap: 12px;
            }

            .filter-group {
                min-width: 100%;
            }

            .segmented-control {
                flex-wrap: wrap;
            }

            .segment-option {
                font-size: 11px;
                padding: 7px 10px;
            }

            .filter-stats {
                width: 100%;
                justify-content: center;
            }

            .clear-filters-btn {
                width: 100%;
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

            .mobile-header,
            .mobile-content {
                display: block;
            }

            .mobile-header {
                display: flex;
            }

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

            .mobile-content {
                padding: 16px;
            }

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

            .mobile-column .exchange-badge {
                width: fit-content;
            }

            .mobile-column .funding-rate {
                font-size: 11px;
            }

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

            .trade-table td:nth-child(2),
            .trade-table td:nth-child(3),
            .trade-table td:nth-child(4),
            .trade-table td:nth-child(5),
            .trade-table td:nth-child(6),
            .trade-table td:nth-child(7),
            .trade-table td:nth-child(8),
            .trade-table td:nth-child(9),
            .trade-table td:nth-child(10),
            .trade-table td:nth-child(11) {
                display: none;
            }

            .trade-table td:first-child>.asset-cell:last-child {
                display: none !important;
            }

            .trade-table td:first-child {
                display: block;
                padding: 0;
            }
        }

        /* ========================================
               LIVE VALUE UPDATE ANIMATIONS
            ======================================== */
        .live-value {
            position: relative;
            transition: color 0.35s ease, transform 0.25s ease, opacity 0.25s ease;
            will-change: transform;
            backface-visibility: hidden;
            transform: translateZ(0);
        }

        .flash-green {
            animation: flashGreen 2.8s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .flash-red {
            animation: flashRed 2.8s cubic-bezier(0.22, 1, 0.36, 1);
        }

        .row-updated {
            animation: rowPulse 1.2s ease;
        }

        .value-pop {
            animation: valuePop 1.4s cubic-bezier(0.22, 1, 0.36, 1);
        }

        @keyframes flashGreen {
            0% {
                background: rgba(16, 185, 129, 0);
                transform: scale(1);
            }

            15% {
                background: rgba(16, 185, 129, 0.22);
                transform: scale(1.03);
            }

            100% {
                background: rgba(16, 185, 129, 0);
                transform: scale(1);
            }
        }

        @keyframes flashRed {
            0% {
                background: rgba(239, 68, 68, 0);
                transform: scale(1);
            }

            15% {
                background: rgba(239, 68, 68, 0.22);
                transform: scale(1.03);
            }

            100% {
                background: rgba(239, 68, 68, 0);
                transform: scale(1);
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

    <style>
        .pagination-wrapper {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 24px;
            padding: 20px 24px;
            background: var(--bg-elevated);
            border: 1px solid var(--border-default);
            border-radius: 12px;
            box-shadow: var(--shadow-md);
            gap: 20px;
            flex-wrap: wrap;
        }

        .pagination-info {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .pagination-text {
            font-size: 13px;
            color: var(--text-secondary);
            font-weight: 500;
            font-family: 'Inter', sans-serif;
        }

        .pagination-text strong {
            color: var(--text-primary);
            font-weight: 700;
        }

        .pagination-controls {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .per-page-selector {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .per-page-label {
            font-size: 12px;
            font-weight: 600;
            color: var(--text-tertiary);
            white-space: nowrap;
            font-family: 'Inter', sans-serif;
        }

        .pagination-select {
            width: 80px;
        }

        .pagination-select select {
            padding: 8px 32px 8px 12px;
            font-size: 12px;
        }

        .pagination-buttons {
            display: flex;
            align-items: center;
            gap: 6px;
        }

        .pagination-btn {
            min-width: 36px;
            height: 36px;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 0 12px;
            background: var(--bg-canvas);
            border: 1px solid var(--border-default);
            border-radius: 8px;
            color: var(--text-primary);
            font-size: 13px;
            font-weight: 600;
            font-family: 'Inter', sans-serif;
            cursor: pointer;
            transition: all 0.2s ease;
            box-shadow: var(--shadow-sm);
        }

        .pagination-btn:hover:not(.pagination-btn-disabled):not(.pagination-btn-active) {
            background: var(--bg-surface);
            border-color: var(--border-strong);
            transform: translateY(-1px);
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.3);
        }

        .pagination-btn:active:not(.pagination-btn-disabled):not(.pagination-btn-active) {
            transform: translateY(0);
        }

        .pagination-btn-active {
            background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(6, 182, 212, 0.1));
            color: var(--cyan);
            border: 1px solid rgba(6, 182, 212, 0.4);
            box-shadow: 0 0 20px rgba(6, 182, 212, 0.2), inset 0 1px 0 rgba(255, 255, 255, 0.1);
            cursor: default;
            font-weight: 700;
        }

        .pagination-btn-arrow {
            padding: 0;
            width: 36px;
        }

        .pagination-btn-arrow svg {
            transition: transform 0.2s ease;
        }

        .pagination-btn-arrow:hover:not(.pagination-btn-disabled) svg {
            transform: scale(1.15);
        }

        .pagination-btn-disabled {
            opacity: 0.3;
            cursor: not-allowed;
            background: var(--bg-canvas);
        }

        .pagination-ellipsis {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 36px;
            height: 36px;
            color: var(--text-tertiary);
            font-weight: 600;
            font-size: 14px;
            user-select: none;
        }

        /* Mobile Pagination */
        @media (max-width: 768px) {
            .pagination-wrapper {
                flex-direction: column;
                padding: 16px;
                gap: 16px;
            }

            .pagination-info {
                width: 100%;
                justify-content: center;
                text-align: center;
            }

            .pagination-text {
                font-size: 12px;
            }

            .pagination-controls {
                width: 100%;
                flex-direction: column;
                gap: 16px;
            }

            .per-page-selector {
                width: 100%;
                justify-content: center;
            }

            .pagination-buttons {
                width: 100%;
                justify-content: center;
                flex-wrap: wrap;
            }

            .pagination-btn {
                min-width: 32px;
                height: 32px;
                font-size: 12px;
            }

            .pagination-btn-arrow {
                width: 32px;
            }

            .pagination-ellipsis {
                width: 32px;
                height: 32px;
            }

            /* Hide some page numbers on very small screens */
            @media (max-width: 400px) {
                .pagination-buttons {
                    gap: 4px;
                }

                .pagination-btn {
                    min-width: 28px;
                    height: 28px;
                    padding: 0 8px;
                    font-size: 11px;
                }
            }
        }

        /* Loading state for pagination */
        .pagination-btn[wire\:loading] {
            opacity: 0.6;
            cursor: wait;
        }

        .pagination-wrapper[wire\:loading] {
            opacity: 0.8;
            pointer-events: none;
        }
    </style>
@endpush

<div class="dashboard" wire:poll.1500ms="refreshTrades">
    <!-- Premium Header -->
    <div class="dashboard-header">
        <div class="title-group">
            <h2 class="title">Live Trading</h2>
            <p class="subtitle">Real-time execution monitor</p>
        </div>
        <div class="header-actions">
            <div class="live-indicator">
                <span class="pulse-dot"></span>
                <span>Live Feed</span>
            </div>
        </div>
    </div>

    <!-- Premium Filter Bar -->
    <div class="filter-bar" x-data="{ 
             statusFilter: @entangle('statusFilter').live, 
             assetFilter: @entangle('assetFilter').live, 
             sortBy: @entangle('sortBy').live 
         }">
        <!-- Status Filter -->
        <div class="filter-group">
            <label class="filter-label">Status</label>
            <div class="segmented-control">
                <button @click="statusFilter = 'all'" :class="{ 'active': statusFilter === 'all' }"
                    class="segment-option">
                    All
                </button>
                <button @click="statusFilter = 'open'" :class="{ 'active': statusFilter === 'open' }"
                    class="segment-option">
                    Open
                </button>
                <button @click="statusFilter = 'closed'" :class="{ 'active': statusFilter === 'closed' }"
                    class="segment-option">
                    Closed
                </button>
                <button @click="statusFilter = 'profit'" :class="{ 'active': statusFilter === 'profit' }"
                    class="segment-option profit">
                    Profit
                </button>
                <button @click="statusFilter = 'loss'" :class="{ 'active': statusFilter === 'loss' }"
                    class="segment-option loss">
                    Loss
                </button>
            </div>
        </div>

        <!-- Asset Filter -->
        <div class="filter-group">
            <label class="filter-label">Asset</label>
            <div class="custom-select">
                <select x-model="assetFilter">
                    <option value="all">All Assets</option>
                    @foreach($availableAssets as $asset)
                        <option value="{{ $asset }}">{{ $asset }}</option>
                    @endforeach
                </select>
            </div>
        </div>

        <!-- Sort By -->
        <div class="filter-group">
            <label class="filter-label">Sort By</label>
            <div class="custom-select">
                <select x-model="sortBy">
                    <option value="latest">Latest First</option>
                    <option value="oldest">Oldest First</option>
                    <option value="highest_profit">Highest Profit</option>
                    <option value="highest_loss">Highest Loss</option>
                </select>
            </div>
        </div>

        <!-- Filter Stats -->
        <div class="filter-stats">
            <span class="filter-stats-label">Showing</span>
            <span class="filter-stats-value">{{ count($trades) }}</span>
            <span class="filter-stats-label">trades</span>
        </div>

        <!-- Clear Filters -->
        <template x-if="statusFilter !== 'all' || assetFilter !== 'all' || sortBy !== 'latest'">
            <button @click="statusFilter = 'all'; assetFilter = 'all'; sortBy = 'latest'" class="clear-filters-btn">
                Clear Filters
            </button>
        </template>
    </div>

    <!-- Responsive Table -->
    <div class="table-wrapper">
        <div class="table-scroll">
            <table class="trade-table">
                <thead>
                    <tr>
                        <th>Asset</th>
                        <th>Time</th>
                        <th>Status</th>
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
                            <!-- MOBILE + DESKTOP STRUCTURE -->
                            <td>
                                <!-- Mobile Header -->
                                <div class="mobile-header">
                                    <div class="asset-cell">
                                        <div class="asset-icon">
                                            {{ substr($trade->asset->symbol, 0, 2) }}
                                        </div>
                                        <span class="asset-name">{{ $trade->asset->symbol }}</span>
                                    </div>
                                    <div class="mobile-header-right">
                                        <span class="mobile-time">{{ $trade->opened_at->format('h:i:s A') }}</span>
                                        <div 
                                            class="mobile-total value-cell {{ $trade->total_net >= 0 ? 'green' : 'red' }}"
                                        >
                                            <span class="value-symbol">$</span>
                                            <span 
                                                class="live-value {{ $trade->total_net >= 0 ? 'green' : 'red' }}"
                                                data-value="{{ $trade->total_net }}" 
                                                data-field="total_net"
                                            >
                                                {{ number_format(abs($trade->total_net), 2) }}
                                            </span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Mobile Content -->
                                <div class="mobile-content">
                                    <div class="mobile-row">
                                        <div class="mobile-column">
                                            <div class="mobile-label">Status</div>
                                            <span class="status-badge status-{{ $trade->status }}">
                                                {{ ucfirst($trade->status) }}
                                            </span>
                                        </div>
                                    </div>

                                    <div class="mobile-row">
                                        <div class="mobile-column">
                                            <div class="mobile-label">Long Exchange</div>
                                            <span class="exchange-badge badge-long">
                                                {{ $trade->long_exchange }}
                                            </span>
                                            <div class="funding-rate">
                                                <span>Rate:</span>
                                                <span
                                                    class="live value funding-rate-value {{ $trade->funding_rate_long >= 0 ? 'positive' : 'negative' }}"
                                                    data-value="{{ $trade->funding_rate_long }}"
                                                    data-field="long_funding_rate">
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
                                                    class="live-value funding-rate-value {{ $trade->funding_rate_short >= 0 ? 'positive' : 'negative' }}"
                                                    data-value="{{ $trade->funding_rate_short }}"
                                                    data-field="short_funding_rate">
                                                    {{ number_format($trade->funding_rate_short * 100, 4) }}%
                                                </span>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="mobile-pnl-grid">
                                        <div class="mobile-pnl-item">
                                            <div class="mobile-pnl-label">Price PnL</div>
                                            <div class="live-value mobile-pnl-value {{ $trade->price_pnl >= 0 ? 'green' : 'red' }}"
                                                data-value="{{ $trade->price_pnl }}" data-field="price_pnl">
                                                ${{ number_format(abs($trade->price_pnl), 2) }}
                                            </div>
                                        </div>
                                        <div class="mobile-pnl-item">
                                            <div class="mobile-pnl-label">Funding</div>
                                            <div class="live-value mobile-pnl-value {{ $trade->funding_profit >= 0 ? 'green' : ($trade->funding_profit < 0 ? 'red' : 'neutral') }}"
                                                data-value="{{ $trade->funding_profit }}" data-field="funding_profit">
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

                                <!-- Desktop Asset -->
                                <div class="asset-cell">
                                    <div class="asset-icon">
                                        {{ substr($trade->asset->symbol, 0, 2) }}
                                    </div>
                                    <span class="asset-name">{{ $trade->asset->symbol }}</span>
                                </div>
                            </td>

                            <!-- Desktop Columns -->
                            <td class="time-cell">{{ $trade->opened_at->format('h:i:s A') }}</td>

                            <td>
                                <span class="status-badge status-{{ $trade->status }}">
                                    {{ ucfirst($trade->status) }}
                                </span>
                            </td>

                            <td>
                                <span class="exchange-badge badge-long">{{ $trade->long_exchange }}</span>
                            </td>

                            <td>
                                <div class="funding-rate">
                                    <span
                                        class="live-value funding-rate-value {{ $trade->funding_rate_long >= 0 ? 'positive' : 'negative' }}"
                                        data-value="{{ $trade->funding_rate_long }}" data-field="long_funding_rate">
                                        {{ number_format($trade->funding_rate_long * 100, 4) }}%
                                    </span>
                                </div>
                            </td>

                            <td>
                                <span class="exchange-badge badge-short">{{ $trade->short_exchange }}</span>
                            </td>

                            <td>
                                <div class="funding-rate">
                                    <span
                                        class="live-value funding-rate-value {{ $trade->funding_rate_short >= 0 ? 'positive' : 'negative' }}"
                                        data-value="{{ $trade->funding_rate_short }}" data-field="short_funding_rate">
                                        {{ number_format($trade->funding_rate_short * 100, 4) }}%
                                    </span>
                                </div>
                            </td>

                            <td>
                                <div class="value-cell">
                                    <span class="value-symbol {{ $trade->price_pnl >= 0 ? 'green' : 'red' }}">$</span>
                                    <span class="live-value {{ $trade->price_pnl >= 0 ? 'green' : 'red' }}"
                                        data-value="{{ $trade->price_pnl }}" data-field="price_pnl">
                                        {{ number_format(abs($trade->price_pnl), 2) }}
                                    </span>
                                </div>
                            </td>

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

                            <td class="red">
                                <div class="value-cell">
                                    <span class="value-symbol red">-$</span>
                                    <span class="red">{{ number_format($trade->fees, 2) }}</span>
                                </div>
                            </td>

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
                            <td colspan="11">
                                <div class="empty-state">
                                    <div class="empty-state-icon">📊</div>
                                    <div class="empty-state-text">No trades match your filters</div>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>


    <!-- Premium Pagination -->
    @if($trades->hasPages())
        <div class="pagination-wrapper">
            <div class="pagination-info">
                <span class="pagination-text">
                    Showing <strong>{{ $trades->firstItem() }}</strong> to <strong>{{ $trades->lastItem() }}</strong> of
                    <strong>{{ $trades->total() }}</strong> trades
                </span>
            </div>

            <div class="pagination-controls">
                <!-- Per Page Selector -->
                <div class="per-page-selector">
                    <label class="per-page-label">Per page:</label>
                    <div class="custom-select pagination-select">
                        <select wire:model.live="perPage">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
                </div>

                <!-- Page Numbers -->
                <div class="pagination-buttons">
                    {{-- Previous Button --}}
                    @if ($trades->onFirstPage())
                        <button class="pagination-btn pagination-btn-disabled" disabled>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    @else
                        <button wire:click="previousPage" class="pagination-btn pagination-btn-arrow">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M10 12L6 8L10 4" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    @endif

                    {{-- Page Numbers --}}
                    @foreach ($trades->getUrlRange(1, $trades->lastPage()) as $page => $url)
                        @if ($page == $trades->currentPage())
                            <button class="pagination-btn pagination-btn-active">
                                {{ $page }}
                            </button>
                        @elseif ($page == 1 || $page == $trades->lastPage() || abs($page - $trades->currentPage()) < 2)
                            <button wire:click="gotoPage({{ $page }})" class="pagination-btn">
                                {{ $page }}
                            </button>
                        @elseif (abs($page - $trades->currentPage()) == 2)
                            <span class="pagination-ellipsis">...</span>
                        @endif
                    @endforeach

                    {{-- Next Button --}}
                    @if ($trades->hasMorePages())
                        <button wire:click="nextPage" class="pagination-btn pagination-btn-arrow">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    @else
                        <button class="pagination-btn pagination-btn-disabled" disabled>
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M6 4L10 8L6 12" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                    stroke-linejoin="round" />
                            </svg>
                        </button>
                    @endif
                </div>
            </div>
        </div>
    @endif

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

                if (!(key in previousValues)) {
                    previousValues[key] = current;
                    return;
                }

                const previous = previousValues[key];

                if (current !== previous) {
                    el.classList.remove('flash-green', 'flash-red', 'value-pop');
                    void el.offsetWidth;

                    if (current > previous) {
                        el.classList.add('flash-green', 'value-pop');
                    } else {
                        el.classList.add('flash-red', 'value-pop');
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