
<div class="invora-card mt-4">

    <!-- HEADER -->

    <div class="invora-header" style="width: 100%;">

        <div class="invora-header-left">
            <div>
                <h6 class="invora-title">Active Investments</h6>
                <span style="font-size:12px; color:var(--text-secondary)">
                    Track your capital in motion
                </span>
            </div>
        </div>

        <div class="invora-header-right">
            <input :disable="false" type="text" wire:model.live="search" placeholder="Search..." class="invora-input datatable-input">

            <select wire:model.live="type" class="invora-input datatable-selector" style="flex: 1;">
                <option value="">All</option>
                <option value="credit">Inflow</option>
                <option value="debit">Outflow</option>
            </select>
        </div>

    </div>

    <!-- DESKTOP TABLE -->
    <div class="invora-table-wrapper">
        <table class="invora-table">
            <thead>
                <tr>
                    <th>Bot</th>
                    <th>Issued Date</th>
                    <th>Purchase Amount</th>
                    <th>Investment Value</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($investments as $txn)
                    <tr class="invora-row" wire:click="viewInvestment({{ $txn->uuid }})" style="cursor:pointer;">
                        <td>
                            <div style="display:flex; align-items:center; gap:10px;">
                                
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
                                    {{ strtoupper(substr($txn->bot->name, 0, 1)) }}
                                </div>

                                <div>
                                    <div style="font-weight:500;">
                                        {{ $txn->bot->name }}
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
                                ${{ number_format($txn->amount,2) }}
                            </span>
                            <div style="font-size:12px; color:var(--text-secondary);">
                                Capital
                            </div>
                        </td>

                        <td>
                            <span class="invora-credit">
                                ${{ number_format($txn->amount,2) }}
                            </span>
                            <div style="font-size:12px; color:var(--text-secondary);">
                                Current Value
                            </div>
                        </td>

                        <td>
                            @if($txn->status->value === 'active')
                                <span class="invora-badge invora-badge-green">Running</span>
                            @elseif($txn->status->value === 'completed')
                                <span class="invora-badge">Completed</span>
                            @elseif($txn->status->value === 'termination_requested')
                                <span class="invora-badge invora-badge-green">Running</span>
                            @else
                                <span class="invora-badge invora-badge-red">Stopped</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:20px; text-align:center;">
                            No transactions
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <div class="invora-mobile">
        @foreach($investments as $txn)
            <a 
                href="{{ route('investments.item', ['id' => $txn->id]) }}" 
                class="invora-invest-card" 
                style="width: 100%"
            >

                <!-- TOP ROW -->
                <div class="invora-invest-top">

                    <div class="invora-invest-bot">
                        <div class="invora-bot-icon">
                            {{ strtoupper(substr($txn->bot->name, 0, 1)) }}
                        </div>

                        <div>
                            <div class="invora-bot-name">
                                {{ $txn->bot->name }}
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

                    <span class="invora-badge 
                        {{ $txn->status->value === 'active' ? 'invora-badge-green' : ($txn->status->value === 'termination_requested' ? 'invora-badge-green' : 'invora-badge-red') }}">
                        {{ $txn->status->value === 'active' ? 'Running' : ($txn->status->value === 'termination_requested' ? 'Running' : 'Stopped') }}
                    </span>

                    <div class="invora-invest-date">
                        {{ $txn->created_at->format('M d, Y • h:i A') }}
                    </div>

                </div>

            </a>
        @endforeach
    </div>

    <!-- PAGINATION -->
    <div style="padding:16px; border-top:1px solid var(--border);">
        {{ $investments->links() }}
    </div>

</div>