<div class="dashboard-wrapper">

    <div class="section-header">
        <h2 class="section-title">License Portfolio</h2>
        <p class="section-subtitle" style="color:var(--text-secondary);">Manage your active trading engine licenses</p>
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

                        <div class="license-status {{ $expired ? 'expired' : 'active' }}">
                            {{ $expired ? 'Expired' : 'Running' }}
                        </div>
                    </div>

                    <div class="license-stats">
                        <div class="stat"><span class="stat-label">Purchase Price</span><span class="stat-value">${{ number_format($license->bot->price ?? 0, 2) }}</span></div>
                        <div class="stat"><span class="stat-label">Expires On</span><span class="stat-value">{{ $license->expires_at->format('M d, Y') }}</span></div>
                    </div>

                    <div class="license-progress-wrapper">
                        <div class="progress-label">License Usage</div>
                        <div class="progress-bar"><div class="progress-fill" style="width: {{ $progress }}%"></div></div>
                    </div>

                    <div class="license-actions">
                        @if(!$expired)
                            <button
                                wire:click="openModal({{ $license->id }})"
                                wire:loading.attr="disabled"
                                wire:target="openModal"
                                class="btn-invest">

                                <span wire:loading.remove wire:target="openModal">
                                    Deploy Capital
                                </span>

                                <span wire:loading wire:target="openModal" class="btn-loader">
                                    <span class="spinner"></span>
                                    Processing...
                                </span>
                            </button>
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

    <x-dashboard.investment-history :investments="$investments" />

</div>

