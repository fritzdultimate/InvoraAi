@php
    $bonuses = $deposit->referralBonuses()->with('user')->orderBy('level')->get();
@endphp

<style>
    .drb { display: grid; gap: 14px; font-size: 14px; }
    .drb-stats { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 10px; }
    .drb-stat { padding: 10px 12px; border-radius: 10px; background: rgba(120,120,140,0.08); }
    .drb-stat small { display: block; font-size: 12px; opacity: .65; }
    .drb-stat strong { display: block; margin-top: 2px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
    .drb-green { color: #16a34a; }
    .dark .drb-green { color: #4ade80; }
    .drb-table { width: 100%; border-collapse: collapse; }
    .drb-table th { text-align: left; font-size: 12px; font-weight: 500; opacity: .65; padding: 8px 10px; }
    .drb-table td { padding: 10px; border-top: 1px solid rgba(120,120,140,0.18); vertical-align: top; }
    .drb-muted { font-size: 12px; opacity: .6; }
    .drb-empty { opacity: .7; }
    @media (max-width: 600px) { .drb-stats { grid-template-columns: minmax(0, 1fr); } }
</style>

<div class="drb">
    <div class="drb-stats">
        <div class="drb-stat"><small>Depositor</small><strong>{{ $deposit->user?->email ?? '—' }}</strong></div>
        <div class="drb-stat"><small>Deposit paid</small><strong>${{ number_format((float) $deposit->actually_paid, 2) }}</strong></div>
        <div class="drb-stat"><small>Total distributed</small><strong class="drb-green">${{ number_format((float) $bonuses->sum('amount'), 2) }}</strong></div>
    </div>

    @if ($bonuses->isEmpty())
        <p class="drb-empty">No referral bonus has been given for this deposit. The user may have no upline, or no referral level is active.</p>
    @else
        <div style="overflow-x: auto">
            <table class="drb-table">
                <thead>
                    <tr><th>Level</th><th>Upline</th><th>%</th><th>Amount</th><th>Status</th></tr>
                </thead>
                <tbody>
                    @foreach ($bonuses as $bonus)
                        <tr>
                            <td>L{{ $bonus->level }}</td>
                            <td>
                                <div>{{ $bonus->user?->name ?? 'Deleted user' }}</div>
                                <div class="drb-muted">{{ $bonus->user?->email }}</div>
                            </td>
                            <td>{{ rtrim(rtrim(number_format((float) $bonus->percent, 2), '0'), '.') }}%</td>
                            <td><strong>${{ number_format((float) $bonus->amount, 2) }}</strong></td>
                            <td>
                                {{ ucfirst($bonus->status) }}
                                @if ($bonus->status === 'pending' && $bonus->claimable_at)
                                    <div class="drb-muted">claimable {{ $bonus->claimable_at->format('M j, Y') }}</div>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @endif
</div>
