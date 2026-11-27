@push('styles')
    <style>
        .invora-row td {
            display: block;
            width: 100%;
            height: 100%;
            text-decoration: none;
            color: inherit;
        }
    </style>
@endpush
<div class="invora-card mt-4">

    <!-- HEADER -->

    <div class="invora-header" style="width: 100%;">

        <div class="invora-header-left">
            <div>
                <h6 class="invora-title">My Investments</h6>
                <span style="font-size:12px; color:var(--text-secondary)">
                    Track your capital in motion
                </span>
            </div>
        </div>

        <div class="invora-header-right">
            <select wire:model.live="statusFilter" class="invora-input datatable-selector" style="flex: 1;">
                <option value="">All</option>
                <option value="active">Running</option>
                <option value="termination_requested">Termination pending</option>
                <option value="completed">Completed</option>
                <option value="terminated">Terminated</option>
            </select>
        </div>

    </div>

    <!-- DESKTOP TABLE -->
    <div class="invora-table-wrapper">
        <table class="invora-table">
            <thead>
                <tr class="invora-row">
                    <th>Bot</th>
                    <th>Issued Date</th>
                    <th>Purchase Amount</th>
                    <th>Investment Value</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody style="display: flex; flex-direction: column; gap: 10px;">
                @forelse($investments as $txn)
                    <tr class="invora-row" onclick="window.location='{{ route('investments.item', $txn->uuid) }}'" style="cursor:pointer;">
                        <td>
                            <div 
                                style="display:flex; align-items:center; gap:10px;"
                            >
                                
                                <div style="
                                    width:36px;
                                    height:36px;
                                    border-radius:10px;
                                    background: linear-gradient(135deg,#6366f1,#22d3ee);
                                    display:flex;
                                    align-items:center;
                                    justify-content:center;
                                    font-size:14px;
                                    font-weight:600;
                                    color:#fff;
                                ">
                                    {{ strtoupper(substr($txn->bot?->name ?? 'B', 0, 1)) }}
                                </div>

                                <div>
                                    <div style="font-weight:500;">
                                        {{ $txn->bot?->name ?? 'Bot' }}
                                    </div>
                                    <div style="font-size:12px; color:var(--text-secondary);">
                                        Bot
                                    </div>
                                </div>

                            </div>
                        </td>

                        <td>
                            <div>{{ $txn->created_at->format('M d') }}</div>
                            <div style="font-size:12px; color:var(--text-secondary);">
                                {{ $txn->created_at->format('h:i A') }}
                            </div>
                        </td>

                        <td>
                            <span class="invora-credit">
                                ${{ number_format($txn->capital ?? $txn->amount, 2) }}
                            </span>
                            <div style="font-size:12px; color:var(--text-secondary);">
                                Capital
                            </div>
                        </td>

                        <td align="right">
                            <span class="invora-credit">
                                ${{ number_format($txn->amount,2) }}
                            </span>
                            <div style="font-size:12px; color:var(--text-secondary);">
                                Current Value
                            </div>
                        </td>

                        <td align="right">
                            <span class="invora-badge {{ $txn->statusBadgeClass() }}">{{ $txn->statusLabel() }}</span>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" style="padding:20px; text-align:center;">
                            No investments found
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="invora-mobile">
        @forelse($investments as $txn)
            <a 
                href="{{ route('investments.item', ['id' => $txn->uuid]) }}" 
                class="invora-invest-card" 
                style="width: 100%"
            >

                <!-- TOP ROW -->
                <div class="invora-invest-top">

                    <div class="invora-invest-bot">
                        <div class="invora-bot-icon">
                            {{ strtoupper(substr($txn->bot?->name ?? 'B', 0, 1)) }}
                        </div>

                        <div>
                            <div class="invora-bot-name">
                                {{ $txn->bot?->name ?? 'Bot' }}
                            </div>
                            <div class="invora-bot-sub">
                                Bot
                            </div>
                        </div>
                    </div>

                    <div class="invora-invest-amount">
                        ${{ number_format($txn->amount, 2) }}
                    </div>

                </div>

                <!-- STATUS + DATE -->
                <div class="invora-invest-bottom">

                    <span class="invora-badge {{ $txn->statusBadgeClass() }}">
                        {{ $txn->statusLabel() }}
                    </span>

                    <div class="invora-invest-date">
                        {{ $txn->created_at->format('M d, Y • h:i A') }}
                    </div>

                </div>

            </a>
        @empty
            <div style="padding:20px; text-align:center; color:var(--text-secondary);">
                No investments found
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div style="padding:16px; border-top:1px solid var(--border);">
        {{ $investments->links() }}
    </div>

</div>