

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
            color: black !important;
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

        <div class="dex-table-scroll" wire:poll.5s="poll">
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
                        <tr 
                            wire:key="trade-{{ $trade->id }}"
                            class="{{ $trade->created_at->greaterThan($visitedAt) ? 'trade-row-new' : '' }}"
                        >
                            <td class="muted">{{ $trade->created_at?->diffForHumans(null, true) }} ago</td>
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
