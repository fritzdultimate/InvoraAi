<div class="dashboard-wrapper">

    <div class="section-header">
        <h2 class="section-title">License Portfolio</h2>
        <p class="section-subtitle">Manage your active trading engine licenses</p>
    </div>

    @if($licenses->isEmpty())
        <div class="empty-state">
            <div class="empty-icon">⟡</div>
            <h4>No Active Licenses</h4>
            <p>You have not activated any trading engines yet.</p>
        </div>
    @else
        <div class="license-grid">
            @foreach($licenses as $license)
                @php
                    $expired = $license->expires_at->isPast();
                    $totalDays = $license->starts_at->diffInDays($license->expires_at);
                    $usedDays = $license->starts_at->diffInDays(now());
                    $progress = $totalDays > 0 ? min(100, ($usedDays / $totalDays) * 100) : 0;
                @endphp

                <div class="license-card">
                    <div class="license-card-top">
                        <div>
                            <h5 class="license-title">{{ $license->bot->name }}</h5>
                            <span class="license-id">License #{{ $license->id }}</span>
                        </div>
                        <div class="license-status {{ $expired ? 'expired' : 'active' }}">{{ $expired ? 'Expired' : 'Active' }}</div>
                    </div>

                    <div class="license-stats">
                        <div class="stat"><span class="stat-label">Purchase Price</span><span class="stat-value">${{ number_format($license->meta['price'] ?? 0, 2) }}</span></div>
                        <div class="stat"><span class="stat-label">Expires On</span><span class="stat-value">{{ $license->expires_at->format('M d, Y') }}</span></div>
                    </div>

                    <div class="license-progress-wrapper">
                        <div class="progress-label">License Usage</div>
                        <div class="progress-bar"><div class="progress-fill" style="width: {{ $progress }}%"></div></div>
                    </div>

                    <div class="license-actions">
                        @if(!$expired)
                            <button wire:click="openModal({{ $license->id }})" class="btn-invest">Deploy Capital</button>
                        @else
                            <button class="btn-disabled" disabled>License Expired</button>
                        @endif
                    </div>
                </div>
            @endforeach
        </div>
    @endif

    
    @if($showModal)
        <div class="modal-overlay" wire:click.self="$set('showModal', false)">
            <div class="modal">

                {{-- Header --}}
                <div class="modal-header flex justify-between items-center">
                    <span>Deploy Capital</span>
                    <button class="modal-close" wire:click="$set('showModal', false)">×</button>
                </div>

                {{-- Info --}}
                <p class="modal-info">
                    Enter the amount to invest for <strong>{{ $selectedLicense->bot->name }}</strong> license.<br>
                    Min: ${{ $selectedLicense->bot->min_amount }} | Max: ${{ $selectedLicense->bot->max_amount }}
                </p>

                {{-- Asset Selector --}}
                <div class="asset-selector">
                    <label>Select Asset</label>
                    <select class="input" wire:model.live="asset">
                        <option value="main">Main Balance</option>
                        <option value="deposit">Deposit Balance</option>
                    </select>
                </div>

                {{-- Amount Input --}}
                <input type="number" class="input" wire:model.defer="amount" placeholder="Enter amount">
                @error('amount') <div class="error">{{ $message }}</div> @enderror

                {{-- Button --}}
                <button wire:click="createInvestment" wire:loading.attr="disabled" class="btn-modal">
                    <span wire:loading.remove wire:target="createInvestment">Deploy</span>
                    <span wire:loading wire:target="createInvestment">Processing...</span>
                </button>

            </div>
        </div>

        <style>
            .modal-overlay {
                position: fixed;
                inset: 0;
                background: rgba(0,0,0,0.6);
                display: flex;
                justify-content: center;
                align-items: center;
                z-index: 1000;
                padding: 16px;
            }
            .modal {
                background: #0f172a;
                border-radius: 20px;
                width: 100%;
                max-width: 400px;
                padding: 24px;
                box-shadow: 0 20px 50px rgba(0,0,0,0.7);
                color: #f9fafb;
                position: relative;
                transition: all 0.3s ease;
            }
            .modal-header {
                font-size: 20px;
                font-weight: 700;
                margin-bottom: 16px;
            }
            .modal-close {
                font-size: 22px;
                font-weight: 700;
                background: none;
                border: none;
                color: #9ca3af;
                cursor: pointer;
                transition: color 0.2s;
            }
            .modal-close:hover { color: #f87171; }
            .modal-info {
                font-size: 14px;
                color: #9ca3af;
                margin-bottom: 16px;
            }
            .input {
                width: 100%;
                padding: 12px;
                border-radius: 12px;
                border: 1px solid #334155;
                background: #1e293b;
                color: #f9fafb;
                margin-bottom: 12px;
                font-size: 14px;
            }
            .error {
                font-size: 12px;
                color: #ef4444;
                margin-bottom: 12px;
            }
            .btn-modal {
                width: 100%;
                padding: 12px;
                border-radius: 12px;
                background: linear-gradient(135deg, #2563eb, #1d4ed8);
                border: none;
                color: #fff;
                font-weight: 600;
                cursor: pointer;
                transition: 0.3s ease;
            }
            .btn-modal:hover { transform: translateY(-2px); box-shadow: 0 10px 25px rgba(37,99,235,0.4); }
            .asset-selector label {
                display: block;
                font-size: 12px;
                color: #9ca3af;
                margin-bottom: 6px;
            }
            .asset-selector select {
                width: 100%;
                padding: 12px;
                border-radius: 12px;
                border: 1px solid #334155;
                background: #1e293b;
                color: #f9fafb;
                font-size: 14px;
                margin-bottom: 12px;
            }

            @media(max-width:480px){
                .modal { padding: 20px; max-width: 100%; }
                .modal-header { font-size: 18px; }
                .input, .asset-selector select, .btn-modal { padding: 10px; font-size: 14px; }
            }
        </style>
    @endif

    <div class="grid grid-cols-12 col-span-12 mt-8">
        <div class="col-span-12">
            <div class="card border-0 overflow-hidden">
                <div class="card-header">
                    <h6 class="card-title mb-0 text-lg">Recent investments</h6>
                </div>
                <div class="card-body">
                    <table id="selection-table"
                        class="border  border-neutral-200 dark:border-neutral-600 rounded-lg border-separate p-4">
                        <thead>
                            <tr>
                                <th scope="col" class="text-neutral-800 dark:text-white">
                                    <div class="form-check style-check flex items-center">
                                        <input class="form-check-input" id="serial" type="checkbox">
                                        <label class="ms-2 form-check-label" for="serial">
                                            S.L
                                        </label>
                                    </div>
                                </th>
                                <th scope="col" class="text-neutral-800 dark:text-white">
                                    <div class="flex items-center gap-2">
                                        Bot
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="text-neutral-800 dark:text-white">
                                    <div class="flex items-center gap-2">
                                        Issued Date
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="text-neutral-800 dark:text-white">
                                    <div class="flex items-center gap-2">
                                        Expires At
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="text-neutral-800 dark:text-white">
                                    <div class="flex items-center gap-2">
                                        Amount
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </div>
                                </th>

                                <th scope="col" class="text-neutral-800 dark:text-white">
                                    <div class="flex items-center gap-2">
                                        Profits
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </div>
                                </th>

                                <th scope="col" class="text-neutral-800 dark:text-white">
                                    <div class="flex items-center gap-2">
                                        Status
                                        <svg class="w-4 h-4 ms-1" aria-hidden="true" xmlns="http://www.w3.org/2000/svg"
                                            width="24" height="24" fill="none" viewBox="0 0 24 24">
                                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round"
                                                stroke-width="2" d="m8 15 4 4 4-4m0-6-4-4-4 4" />
                                        </svg>
                                    </div>
                                </th>
                                <th scope="col" class="text-neutral-800 dark:text-white">
                                    <div class="flex items-center gap-2">
                                        Action
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($investments as $investment)
                                
                            
                                <tr>
                                    <td>
                                        <div class="form-check style-check flex items-center">
                                            <input class="form-check-input" type="checkbox">
                                            <label class="ms-2 form-check-label">
                                                01
                                            </label>
                                        </div>
                                    </td>
                                    <td><a href="javascript:void(0)" class="text-primary-600">{{ ucfirst($investment->bot->name) }}</a></td>
                                    <td>{{ $investment->created_at->format('M d Y') }}</td>
                                    <td>{{ $investment->matures_at->format('M d Y') }}</td>
                                    <td>${{ number_format($investment->amount, 2) }}</td>
                                    <td>${{ number_format(0.33, 2) }}</td>
                                    <td> <span
                                            class="bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 px-6 py-1.5 rounded-full font-medium text-sm">{{ $investment->status }}</span>
                                    </td>
                                    <td>
                                        <a href="javascript:void(0)"
                                            class="w-8 h-8 bg-success-100 dark:bg-success-600/25 text-success-600 dark:text-success-400 rounded-full inline-flex items-center justify-center" style="display: none">
                                            <iconify-icon icon="lucide:edit"></iconify-icon>
                                        </a>
                                        <a href="javascript:void(0)"
                                            class="w-8 h-8 bg-danger-100 dark:bg-danger-600/25 text-danger-600 dark:text-danger-400 rounded-full inline-flex items-center justify-center">
                                            <iconify-icon icon="mingcute:delete-2-line"></iconify-icon>
                                        </a>
                                    </td>
                                </tr>

                            @empty
                                
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

</div>

@push('scripts')
    <script>
    if (document.getElementById("selection-table") && typeof simpleDatatables.DataTable !== 'undefined') {

        let multiSelect = true;
        let rowNavigation = false;
        let table = null;

        const resetTable = function () {
            if (table) {
                table.destroy();
            }

            const options = {
                columns: [
                    { select: [0, 6], sortable: false } // Disable sorting on the first column (index 0 and 6)
                ],
                rowRender: (row, tr, _index) => {
                    if (!tr.attributes) {
                        tr.attributes = {};
                    }
                    if (!tr.attributes.class) {
                        tr.attributes.class = "";
                    }
                    if (row.selected) {
                        tr.attributes.class += " selected";
                    } else {
                        tr.attributes.class = tr.attributes.class.replace(" selected", "");
                    }
                    return tr;
                }
            };
            if (rowNavigation) {
                options.rowNavigation = true;
                options.tabIndex = 1;
            }

            table = new simpleDatatables.DataTable("#selection-table", options);

            // Mark all rows as unselected
            table.data.data.forEach(data => {
                data.selected = false;
            });

            table.on("datatable.selectrow", (rowIndex, event) => {
                event.preventDefault();
                const row = table.data.data[rowIndex];
                if (row.selected) {
                    row.selected = false;
                } else {
                    if (!multiSelect) {
                        table.data.data.forEach(data => {
                            data.selected = false;
                        });
                    }
                    row.selected = true;
                }
                table.update();
            });
        };

        // Row navigation makes no sense on mobile, so we deactivate it and hide the checkbox.
        const isMobile = window.matchMedia("(any-pointer:coarse)").matches;
        if (isMobile) {
            rowNavigation = false;
        }

        resetTable();
    }
</script>
@endpush
