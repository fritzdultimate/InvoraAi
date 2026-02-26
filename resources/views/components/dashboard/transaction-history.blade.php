
<div class="invora-card">

    <!-- HEADER -->

    <div class="invora-header" style="width: 100%;">

        <div class="invora-header-left">
            <h6 class="invora-title">Recent {{ $type === 'withdrawal' ? 'Withdrawals' : 'Deposits' }}</h6>
        </div>

        <div class="invora-header-right">
            <input :disable="false" type="text" wire:model.live="search" placeholder="Search..." class="invora-input">

            <select wire:model.live="type" class="invora-input" style="flex: 1;">
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
                    <th>Txn</th>
                    <th>Status</th>
                    <th>Amount</th>
                    <th>Date</th>
                </tr>
            </thead>

            <tbody>
                @forelse($transactions as $txn)
                    <tr class="invora-row">
                        <td>
                            <div style="font-weight:500;" class="invora-balance-meta">#{{ $txn->reference ?? $txn->id }}</div>
                            <div style="font-size:12px; color:var(--text-primary);">
                                {{ $type === 'withdrawal' ? 'Withdrawal' : 'Deposit' }}
                            </div>
                        </td>

                        <td>
                            <span 
                                class="invora-badges {{ $txn->status }}"
                                style=""
                            >
                                {{ $txn->status }}
                            </span>
                        </td>

                        <td>
                            <span class="{{ $type === 'withdrawal' ? 'invora-debit' : 'invora-credit' }}">
                                ${{ number_format($txn->amount,2) }}
                            </span>
                        </td>

                        <td>
                            <div>{{ $txn->created_at->format('M d') }}</div>
                            <div style="font-size:12px; color:var(--text-secondary);">
                                {{ $txn->created_at->format('h:i A') }}
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="4" style="padding:20px; text-align:center;">
                            No Data yet
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    <!-- MOBILE VIEW (NOW SEXY 🔥) -->
    <div class="invora-mobile">
        @forelse($transactions as $txn)
            <div class="invora-card-item">

                <div style="display:flex; justify-content:space-between;">
                    <strong class="invora-balance-meta">#{{ $txn->reference ?? $txn->id }}</strong>

                    <span class="{{ $type === 'withdrawal' ? 'invora-debit' : 'invora-credit' }}">
                        +${{ number_format($txn->amount,2) }}
                    </span>
                </div>

                <div class="invora-card-row">
                    <span style="color:var(--text-primary);">
                        {{ $type === 'withdrawal' ? 'Withdrawal' : 'Deposit' }}
                    </span>

                    <span class="invora-badge 
                        {{ $txn->status }}">
                        {{ $txn->status }}
                    </span>
                </div>

                <div class="invora-card-row" style="color:var(--text-secondary);">
                    <span>{{ $txn->created_at->format('M d, Y') }}</span>
                    <span>{{ $txn->created_at->format('h:i A') }}</span>
                </div>

            </div>

        @empty
            <div class="empty-state">
                <p style="color: var(--text-secondary);">No Data yet</p>
            </div>
        @endforelse
    </div>

    <!-- PAGINATION -->
    <div style="padding:16px; border-top:1px solid var(--border);">
        {{ $transactions->links() }}
    </div>

</div>