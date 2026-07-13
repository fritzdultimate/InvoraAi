<!-- view/livewire/dashboard/live-trading.blade.php -->

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
            flex-wrap: wrap;
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
            color: black !important;
            font-weight: 600;
        }

        .dex-search {
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

        .coin-badge {
            font-size: 10px;
            padding: 3px 8px;
            border-radius: 6px;
            font-weight: 600;
            background: rgba(255,255,255,0.06);
            color: #d1d5db;
        }
        .coin-btc { background: rgba(247,147,26,0.15); color: #fbb454; }
        .coin-eth { background: rgba(99,102,241,0.15); color: #a5b4fc; }
        .coin-sol { background: rgba(20,241,149,0.15); color: #6ee7b7; }
        .coin-arb { background: rgba(56,189,248,0.15); color: #7dd3fc; }
        .coin-avax { background: rgba(232,65,66,0.15); color: #f87171; }
        .coin-doge { background: rgba(186,166,84,0.15); color: #d4c887; }
        .coin-link { background: rgba(42,98,224,0.15); color: #93c5fd; }
        .coin-op { background: rgba(255,4,32,0.15); color: #fca5a5; }

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

        .trade-row-new {
            animation: rowFadeIn 1.2s ease-out;
            background: rgba(34, 197, 94, 0.06);
        }

        @keyframes rowFadeIn {
            from { background: rgba(34, 197, 94, 0.25); }
            to { background: rgba(34, 197, 94, 0.06); }
        }
    </style>
@endpush

<div>

    <div class="dex-table-wrapper">

        <div class="dex-toolbar">
            <div class="dex-toolbar-left">
                <div class="dex-pills">
                    <button wire:click="$set('coin', 'all')" class="dex-pill {{ $coin === 'all' ? 'active' : '' }}">All</button>
                    @foreach($coinOptions as $option)
                        <button wire:click="$set('coin', '{{ $option }}')" class="dex-pill {{ $coin === $option ? 'active' : '' }}">{{ $option }}</button>
                    @endforeach
                </div>
            </div>

            <div class="dex-toolbar-right">
                <div class="dex-live-badge">
                    <span class="dex-live-dot"></span> Live
                </div>
                <input type="text" wire:model.live.debounce.400ms="search" placeholder="Search coin or tx hash..." class="dex-search">
            </div>
        </div>

        <div class="dex-table-scroll" wire:poll.5s="poll">
            <table class="dex-table">
                <thead>
                    <tr>
                        <th>Time</th>
                        <th>Market</th>
                        <th>Side</th>
                        <th class="num">Price</th>
                        <th class="num">Amount</th>
                        <th class="num">Value (USD)</th>
                        <th>Tx</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($trades as $trade)
                        <tr 
                            wire:key="trade-{{ $trade->id }}"
                            class="{{ $trade->created_at->greaterThan($visitedAt) ? 'trade-row-new' : '' }}"
                        >
                            <td class="muted">{{ $trade->created_at?->diffForHumans(null, true) }} ago</td>
                            <td>
                                <span class="coin-badge coin-{{ strtolower($trade->coin ?? $trade->base_symbol) }}">{{ strtoupper($trade->coin ?? $trade->base_symbol) }}</span>
                                <span class="pair">{{ $trade->pair }}</span>
                            </td>
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
                                @if($trade->explorer_url)
                                    <a href="{{ $trade->explorer_url }}" target="_blank" rel="noopener" class="tx-btn">
                                        {{ $trade->explorer_label }} <span class="tx-arrow">↗</span>
                                    </a>
                                @else
                                    <span class="muted">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="empty-row">No trades match your filters yet.</td>
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