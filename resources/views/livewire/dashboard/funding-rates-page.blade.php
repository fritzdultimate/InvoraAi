@push('styles')
    <style>
        .fr-wrapper {
            margin-top: 20px;
        }

        .fr-section {
            background: rgba(255,255,255,0.02);
            border: 1px solid rgba(255,255,255,0.06);
            border-radius: 18px;
            overflow: hidden;
            margin-bottom: 28px;
        }

        .fr-section-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 18px 22px;
            border-bottom: 1px solid rgba(255,255,255,0.06);
        }

        .fr-section-title {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            color: #d1d5db;
        }

        .fr-section-sub {
            font-size: 11px;
            color: #6b7280;
            margin-top: 3px;
        }

        .fr-live {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 11px;
            color: #22c55e;
        }

        .fr-live-dot {
            width: 6px;
            height: 6px;
            border-radius: 50%;
            background: #22c55e;
            animation: pulse 1.6s infinite;
        }

        .fr-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }

        .fr-table thead th {
            text-align: left;
            padding: 12px 22px;
            font-size: 11px;
            text-transform: uppercase;
            letter-spacing: 0.04em;
            color: #6b7280;
            background: rgba(255,255,255,0.015);
        }

        .fr-table thead th.exchange-col {
            text-align: center;
        }

        .fr-table tbody td {
            padding: 16px 22px;
            border-top: 1px solid rgba(255,255,255,0.05);
            vertical-align: middle;
        }

        .fr-table tbody tr:hover {
            background: rgba(255,255,255,0.015);
        }

        .fr-coin {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .fr-coin-icon {
            width: 30px;
            height: 30px;
            border-radius: 50%;
            background: rgba(34,197,94,0.1);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: #4ade80;
        }

        .fr-coin-name {
            font-weight: 600;
            color: #f3f4f6;
        }

        .fr-cell {
            text-align: center;
        }

        .fr-rate {
            font-weight: 700;
            font-size: 14px;
            font-variant-numeric: tabular-nums;
        }

        .fr-rate.positive { color: #4ade80; }
        .fr-rate.negative { color: #f87171; }

        .fr-daily {
            font-size: 10.5px;
            color: #6b7280;
            margin-top: 2px;
            font-variant-numeric: tabular-nums;
        }

        .fr-daily.positive { color: #22c55e; opacity: 0.7; }
        .fr-daily.negative { color: #ef4444; opacity: 0.7; }

        .fr-na {
            color: #4b5563;
            font-size: 12px;
        }

        @keyframes pulse {
            0% { opacity: 0.3; }
            50% { opacity: 1; }
            100% { opacity: 0.3; }
        }

        @media (max-width: 768px) {
            .fr-table { font-size: 12px; }
            .fr-table thead th, .fr-table tbody td { padding: 10px 12px; }
        }
    </style>
@endpush

<div wire:poll.1500ms="poll" class="fr-wrapper">

    @foreach([
        ['key' => 'stablecoin', 'data' => $stablecoin, 'title' => 'Stablecoin Margined', 'sub' => 'USDT-settled perpetual contracts'],
        ['key' => 'coin', 'data' => $coinMargined, 'title' => 'Coin Margined', 'sub' => 'Inverse perpetual contracts settled in the base asset'],
    ] as $section)

        <div class="fr-section">

            <div class="fr-section-header">
                <div>
                    <div class="fr-section-title">{{ $section['title'] }}</div>
                    <div class="fr-section-sub">{{ $section['sub'] }}</div>
                </div>
                <div class="fr-live">
                    <span class="fr-live-dot"></span> Live
                </div>
            </div>

            <table class="fr-table">
                <thead>
                    <tr>
                        <th>Coin</th>
                        <th class="exchange-col">Binance</th>
                        <th class="exchange-col">Bybit</th>
                        <th class="exchange-col">OKX</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($section['data'] as $coin => $rates)
                        <tr>
                            <td>
                                <div class="fr-coin">
                                    <div class="fr-coin-icon">{{ substr($coin, 0, 1) }}</div>
                                    <div class="fr-coin-name">{{ $coin }}</div>
                                </div>
                            </td>
                            @foreach(['binance', 'bybit', 'okx'] as $exchange)
                                <td class="fr-cell">
                                    @php $rate = $rates->firstWhere('exchange', $exchange); @endphp
                                    @if($rate)
                                        <div class="fr-rate {{ $rate->funding_rate >= 0 ? 'positive' : 'negative' }}">
                                            {{ number_format($rate->funding_rate, 4) }}%
                                        </div>
                                        <div class="fr-daily {{ $rate->daily_rate >= 0 ? 'positive' : 'negative' }}">
                                            {{ number_format($rate->daily_rate, 4) }}% / day
                                        </div>
                                    @else
                                        <span class="fr-na">—</span>
                                    @endif
                                </td>
                            @endforeach
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" style="text-align:center; padding:40px; color:#6b7280;">
                                Loading funding rates...
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>

        </div>

    @endforeach

</div>