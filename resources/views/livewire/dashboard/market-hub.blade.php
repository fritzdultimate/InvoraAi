@push('styles')
    <style>
        .hub-wrapper {
            padding: 60px 20px;
            max-width: 1100px;
            margin: 0 auto;
        }

        .hub-header {
            text-align: center;
            margin-bottom: 48px;
        }

        .hub-badge {
            display: inline-block;
            padding: 6px 14px;
            border-radius: 999px;
            font-size: 12px;
            background: rgba(34, 197, 94, 0.15);
            color: #22c55e;
            margin-bottom: 16px;
        }

        .hub-title {
            font-size: 32px;
            font-weight: 600;
            letter-spacing: -0.02em;
            color: #f9fafb;
            line-height: 1.2;
        }

        .hub-sub {
            font-size: 15px;
            color: #9ca3af;
            margin-top: 14px;
            max-width: 460px;
            margin-inline: auto;
            line-height: 1.6;
        }

        .hub-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }

        .hub-card {
            position: relative;
            background: linear-gradient(145deg, rgba(255,255,255,0.04), rgba(255,255,255,0.02));
            border: 1px solid rgba(255,255,255,0.08);
            border-radius: 20px;
            padding: 32px 24px;
            cursor: pointer;
            transition: 0.25s;
            overflow: hidden;
        }

        .hub-card:hover:not(.disabled) {
            transform: translateY(-6px);
            border-color: rgba(34, 197, 94, 0.5);
            box-shadow: 0 16px 40px rgba(34, 197, 94, 0.12);
        }

        .hub-card.disabled {
            cursor: not-allowed;
            opacity: 0.55;
        }

        .hub-icon {
            width: 52px;
            height: 52px;
            border-radius: 14px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 26px;
            margin-bottom: 20px;
            background: rgba(34, 197, 94, 0.1);
            color: #22c55e;
        }

        .hub-card.disabled .hub-icon {
            background: rgba(156, 163, 175, 0.1);
            color: #9ca3af;
        }

        .hub-card-title {
            font-size: 18px;
            font-weight: 700;
            color: #f3f4f6;
            margin-bottom: 8px;
        }

        .hub-card-desc {
            font-size: 13px;
            color: #9ca3af;
            line-height: 1.5;
            margin-bottom: 20px;
        }

        .hub-card-footer {
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .hub-status {
            font-size: 11px;
            font-weight: 700;
            padding: 4px 10px;
            border-radius: 999px;
        }

        .status-live {
            background: rgba(34, 197, 94, 0.15);
            color: #4ade80;
        }

        .status-soon {
            background: rgba(156, 163, 175, 0.15);
            color: #9ca3af;
        }

        .hub-arrow {
            color: #22c55e;
            font-size: 18px;
            transition: 0.2s;
        }

        .hub-card:hover:not(.disabled) .hub-arrow {
            transform: translateX(4px);
        }

        .hub-card.disabled .hub-arrow {
            color: #6b7280;
        }

        @media (max-width: 900px) {
            .hub-grid { grid-template-columns: 1fr; }
            .hub-title { font-size: 24px; }
        }

        .hub-eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            font-size: 12px;
            font-weight: 600;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            color: #6ee7a8;
            margin-bottom: 20px;
        }

        .hub-eyebrow-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #22c55e;
            box-shadow: 0 0 0 4px rgba(34, 197, 94, 0.15);
            animation: pulse 1.8s infinite;
        }
    </style>
@endpush

<div class="hub-wrapper">

    <div class="hub-header">
        <div class="hub-eyebrow">
            <span class="hub-eyebrow-dot"></span>
            Live Market Intelligence
        </div>
        <h1 class="hub-title">Explore live trading activity</h1>
        <p class="hub-sub">
            Real-time trade data streamed directly from exchanges and the blockchain — pick a market to start watching.
        </p>
    </div>

    <div class="hub-grid">

        <div class="hub-card" wire:click="goToCex">
            <div class="hub-icon">
                <iconify-icon icon="mdi:bank-outline"></iconify-icon>
            </div>
            <div class="hub-card-title">Centralized Exchanges</div>
            <div class="hub-card-desc">
                Live order flow from Binance, Bybit, and other major CEXs — real-time prices, volume, and trade direction.
            </div>
            <div class="hub-card-footer">
                <span class="hub-status status-live">● Live</span>
                <span class="hub-arrow">→</span>
            </div>
        </div>

        <div class="hub-card" wire:click="goToDex">
            <div class="hub-icon">
                <iconify-icon icon="mdi:swap-horizontal-circle-outline"></iconify-icon>
            </div>
            <div class="hub-card-title">Decentralized Exchanges</div>
            <div class="hub-card-desc">
                On-chain swaps across Uniswap, PancakeSwap, and more — verified directly against the blockchain.
            </div>
            <div class="hub-card-footer">
                <span class="hub-status status-live">● Live</span>
                <span class="hub-arrow">→</span>
            </div>
        </div>

        <div class="hub-card disabled">
            <div class="hub-icon">
                <iconify-icon icon="mdi:percent-outline"></iconify-icon>
            </div>
            <div class="hub-card-title">Funding Rates</div>
            <div class="hub-card-desc">
                Track perpetual futures funding rates across exchanges to spot arbitrage opportunities.
            </div>
            <div class="hub-card-footer">
                <span class="hub-status status-soon">Coming Soon</span>
                <span class="hub-arrow">→</span>
            </div>
        </div>

    </div>

</div>