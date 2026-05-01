@push('styles')
    <style>
        .dashboard {
            padding: 20px;
            background: #0f172a;
            color: #e2e8f0;
        }

        .title {
            font-size: 22px;
            margin-bottom: 20px;
        }

        .trade-table {
            width: 100%;
            border-collapse: collapse;
        }

        .trade-table th,
        .trade-table td {
            padding: 12px;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
        }

        .green {
            color: #22c55e;
        }

        .red {
            color: #ef4444;
        }
    </style>
@endpush
<div class="dashboard">

    <h2 class="title">Live Trading Simulation</h2>

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
            @foreach($trades as $trade)
                <tr>
                    <td>{{ $trade->asset->symbol }}</td>
                    <td>{{ $trade->opened_at->format('H:i:s') }}</td>

                    <td class="badge long">{{ $trade->long_exchange }}</td>
                    <td>{{ $trade->short_exchange }}</td>

                    <td class="{{ $trade->price_pnl >= 0 ? 'green' : 'red' }}">
                        {{ number_format($trade->price_pnl, 2) }}
                    </td>

                    <td>{{ number_format($trade->funding_profit, 2) }}</td>

                    <td>-{{ number_format($trade->fees, 2) }}</td>

                    <td class="{{ $trade->total_net >= 0 ? 'green' : 'red' }}">
                        {{ number_format($trade->total_net, 2) }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>

</div>