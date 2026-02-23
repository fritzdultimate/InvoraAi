
<div class="invora-card">

    <!-- HEADER -->

    <div class="invora-header" style="width: 100%;">

        <div class="invora-header-left">
            <h6 class="invora-title">My Licenses</h6>
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
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>
                @forelse($licenses as $lcs)
                    <tr class="invora-row">
                        <td>
                            <div style="font-weight:500;">#{{ $lcs->bot->name }}</div>
                            <div style="font-size:12px; color:var(--text-secondary);">
                                <!-- {{ $lcs->ref }} -->
                            </div>
                        </td>

                        <td>
                            <div>{{ $lcs->created_at->format('M d') }}</div>
                            <div style="font-size:12px; color:var(--text-secondary);">
                                {{ $lcs->created_at->format('h:i A') }}
                            </div>
                        </td>

                        <td>
                            <span class="invora-credit">
                                ${{ number_format($lcs->amount,2) }}
                            </span>
                        </td>

                        <td>
                            <span class="invora-badge {{ $lcs->status === 'active' ? 'invora-badge-green' : 'invora-badge-red' }}"">
                                {{ $lcs->status }}
                            </span>
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

    <!-- MOBILE VIEW (NOW SEXY 🔥) -->
    <div class="invora-mobile">
        @foreach($licenses as $lcs)
            <div class="invora-card-item">

                <div style="display:flex; justify-content:space-between;">
                    <strong>{{ $lcs->bot->name }}</strong>
                    
                    <span class="invora-credit">
                        +${{ number_format($lcs->amount,2) }}
                    </span>
                </div>

                <div class="invora-card-row">

                    <span class="invora-badge {{ $lcs->status === 'active' ? 'invora-badge-green' : 'invora-badge-red' }}">
                        {{ $lcs->status }}
                    </span>
                </div>

                <div class="invora-card-row" style="color:var(--text-secondary);">
                    <span>{{ $lcs->created_at->format('M d, Y') }}</span>
                    <span>{{ $lcs->created_at->format('h:i A') }}</span>
                </div>

            </div>
        @endforeach
    </div>

    <!-- PAGINATION -->
    <div style="padding:16px; border-top:1px solid var(--border);">
        {{ $licenses->links() }}
    </div>

</div>