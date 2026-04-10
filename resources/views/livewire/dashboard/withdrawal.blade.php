@push('styles')
    <!-- Convertion modal styles -->
     <style>
        /* MODAL OVERLAY */
        .invora-modal-overlay {
            position: fixed;
            inset: 0;
            background: rgba(2, 6, 23, 0.75);
            backdrop-filter: blur(8px);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 9999;
        }

        /* MODAL CARD */
        .invora-modal-card {
            width: 100%;
            max-width: 420px;
            background: linear-gradient(145deg, #020617, #0f172a);
            border-radius: 20px;
            padding: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.6);
            animation: scaleIn 0.25s ease;
        }

        @keyframes scaleIn {
            from { transform: scale(0.95); opacity: 0; }
            to { transform: scale(1); opacity: 1; }
        }

        /* HEADER */
        .invora-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 15px;
        }

        .invora-modal-header h3 {
            font-size: 18px;
            font-weight: 600;
        }

        /* SELECT GRID */
        .invora-select-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .invora-select-grid button {
            padding: 12px;
            border-radius: 12px;
            background: #020617;
            border: 1px solid rgba(255,255,255,0.05);
            text-align: left;
            transition: 0.2s;
        }

        .invora-select-grid button span {
            display: block;
            font-size: 12px;
            color: #94a3b8;
        }

        .invora-select-grid button.active {
            border-color: #3b82f6;
            background: rgba(59,130,246,0.1);
        }

        /* STATIC BOX */
        .invora-static-box {
            padding: 12px;
            border-radius: 12px;
            background: #020617;
            border: 1px solid rgba(255,255,255,0.05);
        }

        /* SECONDARY BUTTON */
        .invora-btn-secondary {
            padding: 10px 16px;
            border-radius: 12px;
            background: transparent;
            border: 1px solid rgba(255,255,255,0.1);
            transition: 0.2s;
        }

        .invora-btn-secondary:hover {
            background: rgba(255,255,255,0.05);
        }
     </style>
@endpush
<div class="invora-container" x-data="{selectedWallet: @entangle('networks')}">

    <div class="invora-deposit-page" x-data="compoundModal()">

        

        <div class="invora-balance-card">

            <div class="invora-balance-top">
                <div>
                    <div class="invora-balance-title">Withdrawable Balance</div>

                    <div class="invora-balance-amount">
                        ${{ number_format(auth()->user()->main_balance, 2) }}
                    </div>

                    <div class="invora-balance-meta">
                        Funds ready for withdrawal
                    </div>
                </div>
            </div>

        </div>

        <div class="mt-4 flex gap-2">
            <button 
                class="invora-btn-secondary"
                @click="$dispatch('open-convert-modal')"
            >
                Convert Balance ⇄
            </button>
        </div>

        <div class="mt-2 flex">
            <button 
                class="invora-btn-secondary"
                @click="showCompound = !showCompound"
            >
                Compound Profit ↗
            </button>
        </div>

        <div>
            <div 
                x-show="showCompound"
                x-transition
                class="invora-compound-select mt-3"
                x-cloak
            >

                <div class="compound-select-header">
                    <h4>Select Investment</h4>
                    <span>Choose where to reinvest your profit</span>
                </div>

                <div class="compound-invest-list">

                    @php
                        $investments = auth()->user()->botInvestments()->where('status', 'active')->get();
                        $maxProfit = $investments->max('total_profit');

                        $investments = $investments->map(function ($inv) use ($maxProfit) {
                            $inv->roi = $inv->amount > 0 
                                ? ($inv->total_profit / $inv->amount) * 100 
                                : 0;

                            $inv->is_best = $inv->total_profit == $maxProfit;

                            return $inv;
                        });
                    @endphp

                    @forelse($investments as $inv)

                        <a 
                            href="{{ route('investments.item', $inv->id) }}"
                            class="compound-invest-card {{ $inv->is_best ? 'best' : '' }}"
                        >
                            <div>
                                <h5>
                                    {{ $inv->bot->name }}

                                    @if($inv->is_best)
                                        <span class="badge">Recommended</span>
                                    @endif
                                </h5>

                                <p>#{{ $inv->id }}</p>
                            </div>

                            <div class="text-right">
                                <strong>${{ number_format($inv->total_profit, 2) }}</strong>
                                <span>profit</span>

                                <small class="roi">
                                    {{ number_format($inv->roi, 2) }}% ROI
                                </small>
                            </div>

                            <i class="ri-arrow-right-s-line"></i>
                        </a>
                    @empty
                        <div class="invora-empty">
                            No active investments available
                        </div>
                    @endforelse

                </div>

            </div>
        </div>

        <div class="invora-withdrawal-notice">
            <h4>Withdrawal Notice</h4>

            <ul>
                <li>Minimum withdrawal: <strong>${{ $this->minimumWithdrawalAmount() }}</strong></li>
                <li>Maximum per transaction: <strong>$1,000,000</strong></li>
                <li>Withdrawals are processed within <strong>24 hours</strong></li>
                <li>Only <strong>one withdrawal request per day</strong> is allowed</li>
                <li>A <strong>2% processing fee</strong> applies to all withdrawals</li>
                <li>Please <strong>double-check your wallet address</strong> before submitting</li>
            </ul>

            <div class="invora-warning">
                ⚠️ Transactions sent to incorrect addresses cannot be recovered.
            </div>
        </div>

        <!-- Withdrawal FORM -->
        <div class="invora-carda invora-deposit-card">

            <div class="invora-card-header">
                <h3>Secure Withdrawal</h3>
                <!-- <span style="font-size: 13px; color: var(--secondary)" class="text-gray-600 text-secondary">Secure crypto funding</span> -->
            </div>

            <form wire:submit.prevent="prepareWithdrawal" class="invora-form-pro mt-4">

                <!-- WALLET -->
                <div class="invora-field">
                    <label>Currency</label>

                    <a 
                        class="invora-select-box" 
                        data-modal-toggle="default-modal" 
                        data-modal-target="default-modal" 
                        
                        data-modal-target="default-modal" 
                        data-modal-toggle="default-modal" 
                        href="javascript:void(0)"
                    >
                        @if ($selectedWallet)
                            <div class="invora-select-content">
                                <img src="{{ asset('assets/images/currency/' . strtolower($selectedWallet['code']) . '.png') }}">
                                <span>{{ $selectedWallet['name'] }}</span>
                            </div>
                        @else
                            <span class="invora-placeholder">Select wallet</span>
                        @endif

                        <i class="ri-arrow-down-s-line"></i>
                    </a>

                    @error('selectedWallet')
                        <span class="invora-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- NETWORK -->
                @if(!empty($networks))
                    <div class="invora-field">
                        <label>Network</label>

                        <div class="invora-select-wrapper">
                            <select wire:model="network">
                                @foreach($networks as $network)
                                    <option value="{{ $network['id'] }}">{{ $network['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                @endif

                <!-- AMOUNT -->
                <div class="invora-field">
                    <label>Amount</label>

                    <div class="invora-input-pro">
                        <span class="prefix">$</span>
                        <input 
                            wire:model.live="amount" 
                            type="text" 
                            placeholder="0.00"
                            inputmode="decimal"
                            id="amountInputx"
                            class="deposit-amount"
                        >
                    </div>

                    <div class="invora-hint">
                        Minimum withdrawal: ${{ $this->minimumWithdrawalAmount() }}
                    </div>

                    @error('amount')
                        <span class="invora-error">{{ $message }}</span>
                    @enderror
                </div>

                <!-- Address -->
                <div class="invora-field">
                    <label>Recipient Address</label>

                    <div class="invora-input-pro">
                        <input 
                            wire:model.defer="address" 
                            type="text" 
                            placeholder="0x...., etc."
                        >
                    </div>

                    @error('address')
                        <span class="invora-error">{{ $message }}</span>
                    @enderror
                </div>

                <div class="flex justify-center">
                    @error('general')
                        <span class="invora-error">{{ $message }}</span>
                    @enderror
                </div>

                @if($amount > 0)
                    <div class="invora-summary-box">
                        <div class="invora-summary-row">
                            <span>Amount</span>
                            <span>${{ number_format((float)$amount ?: 0, 2) }}</span>
                        </div>

                        <div class="invora-summary-row fee">
                            <span>Fee (2%)</span>
                            <span>
                                -${{ number_format(((float)$amount ?: 0) * 0.02, 2) }}
                            </span>
                        </div>

                        <div class="invora-summary-divider"></div>

                        <div class="invora-summary-row total">
                            <span>You will receive</span>
                            <span>
                                ${{ number_format(((float)$this->netAmount ?: 0), 2) }}
                            </span>
                        </div>
                    </div>
                @endif

                <!-- BUTTON -->
                <button class="invora-btn-pro">

                    <span wire:loading.remove wire:target="prepareWithdrawal">
                        Withdraw →
                    </span>

                    <span wire:loading wire:target="prepareWithdrawal">
                        Processing...
                    </span>

                </button>

            </form>

        </div>

        <x-action-message 
            :showConfirm="$showConfirm" 
            :type="$type" 
            :title="$title" 
            :message="$text" 
            :warning="$warning"
            :icon="$icon"
        />


    </div>

    <!-- Withdrawal History -->
    <x-dashboard.transaction-history :type="'withdrawal'" :transactions="$withdrawals" />

    <!-- Modal Select Currecny Start -->
    <div id="default-modal" tabindex="-1" aria-hidden="true"
        class="hidden overflow-y-auto overflow-x-hidden fixed top-0 right-0 left-0 z-50 justify-center items-center w-full md:inset-0 h-[calc(100%-1rem)] max-h-full">
        <div class="relative p-4 w-full max-w-2xl max-h-full">
            <!-- Modal content -->
            <div class="relative bg-white rounded-lg shadow dark:bg-gray-700">
                <!-- Modal header -->
                <div class="flex items-center justify-between px-6 py-3 border-b rounded-t dark:border-gray-600">
                    <h5 class="modal-title text-xl" id="exampleModalEditLabel">Select Wallet</h5>
                    <button type="button"
                        class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 ms-auto inline-flex justify-center items-center dark:hover:bg-gray-600 dark:hover:text-white"
                        data-modal-hide="default-modal">
                        <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                            viewBox="0 0 14 14">
                            <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                        </svg>
                        <span class="sr-only">Close modal</span>
                    </button>
                </div>
                <!-- Modal body -->
                <div class="flex flex-col gap-2 p-6">
                    @forelse ($currencies as $currency)
                        <a href="javascript:void(0)" wire:click="selectWallet({{  $currency->id }})" data-modal-hide="default-modal"
                            class="flex items-center justify-between gap-2 p-4 border border-neutral-200 dark:border-neutral-600 rounded-lg hover:bg-neutral-50 dark:hover:bg-neutral-600">
                            <span class="flex items-center gap-4">
                                <img src="{{ asset('assets/images/currency/' . strtolower($currency->code) . '.png') }}"
                                    alt="" class="shrink-0 me-3 overflow-hidden">
                                <span class="text-base mb-0 font-medium text-neutral-600 dark:text-neutral-200 block">
                                    {{ $currency->name }}
                                </span>
                            </span>
                            <span class="text-secondary-light text-base"><i class="ri-arrow-right-s-line"></i></span>
                        </a>
                    @empty
                        <p>No wallet available now.</p>
                    @endforelse

                </div>
            </div>
        </div>
    </div>
    <!-- Modal Select Currecny End -->

    <!-- Convert asset -->
    <div 
        x-data="convertModal()" 
        x-show="open"
        x-cloak
        @open-convert-modal.window="openModal()"
        class="invora-modal-overlay"
    >
        <div class="invora-modal-card">

            <!-- HEADER -->
            <div class="invora-modal-header">
                <h3>Convert Balance</h3>
                <button @click="close()">✕</button>
            </div>

            <!-- BODY -->
            <div class="invora-modal-body">

                <!-- FROM -->
                <div class="invora-field">
                    <label>From Balance</label>

                    <div class="invora-select-grid">
                        <button 
                            :class="from === 'profit' ? 'active' : ''"
                            @click="from = 'profit'"
                        >
                            Profit
                            <span>${{ number_format(auth()->user()->profit_balance, 2) }}</span>
                        </button>

                        <button 
                            :class="from === 'referral' ? 'active' : ''"
                            @click="from = 'referral'"
                        >
                            Referral
                            <span>${{ number_format(auth()->user()->referral_balance, 2) }}</span>
                        </button>
                    </div>
                </div>

                <!-- TO -->
                <div class="invora-field">
                    <label>To</label>
                    <div class="invora-static-box">
                        Main Balance
                    </div>
                </div>

                <!-- AMOUNT -->
                <div class="invora-field">
                    <label>Amount</label>

                    <div class="invora-input-pro">
                        <span class="prefix">$</span>
                        <input 
                            type="number"
                            x-model="amount"
                            placeholder="0.00"
                            inputmode="decimal"
                        >
                    </div>
                </div>

                <!-- SUMMARY -->
                <div class="invora-summary-box">
                    <div class="invora-summary-row">
                        <span>You will receive</span>
                        <span x-text="'$' + format(amount)"></span>
                    </div>
                </div>

                <!-- ACTION -->
                <button 
                    class="invora-btn-pro w-full mt-3"
                    @click="submit()"
                    :disabled="loading"
                >
                    <span x-show="!loading">Continue →</span>
                    <span x-show="loading">Processing...</span>
                </button>

            </div>
        </div>
    </div>

    <div 
        x-data="confirmModal()" 
        x-show="open"
        x-cloak
        class="modal-overlay"
    >
        <div class="modal-container">

            <!-- HEADER -->
            <div class="modal-header">
                <h3 x-text="title"></h3>
                <button @click="close()" class="modal-close-btn">✕</button>
            </div>

            <!-- BODY -->
            <div class="modal-body">

                <p class="text-sm text-gray-400 mb-4" x-text="message"></p>

                <!-- SUMMARY -->
                <div class="invora-summary-box mb-4" x-show="showSummary">
                    <div class="invora-summary-row">
                        <span>Amount</span>
                        <span x-text="amount"></span>
                    </div>

                    <div class="invora-summary-row fee">
                        <span>Fee</span>
                        <span x-text="fee"></span>
                    </div>

                    <div class="invora-summary-divider"></div>

                    <div class="invora-summary-row total">
                        <span>You will receive</span>
                        <span x-text="total"></span>
                    </div>
                </div>

                <!-- OTP -->
                <div x-show="requireOtp" class="mb-4">
                    <label class="modal-label">Enter OTP</label>
                    <input 
                        type="text" 
                        x-model="otp"
                        class="modal-select"
                        placeholder="Enter 6-digit OTP"
                    >
                </div>

                <!-- WARNING -->
                <div class="invora-warning mb-4" x-show="warning">
                    <span x-text="warning"></span>
                </div>

                <!-- ACTIONS -->
                <div class="gap-2" style="display: flex; align-items:center;">
                    <button class="secondary-btn" @click="close()">Cancel</button>

                    <button 
                        class="subscribe-btn"
                        @click="confirm()"
                        :disabled="loading"
                        style="margin-top: 0px !important"
                    >
                        <span x-show="!loading">Confirm</span>
                        <span x-show="loading" class="btn-loader">
                            <span class="spinner"></span> Processing...
                        </span>
                    </button>
                </div>

            </div>
        </div>
    </div>


</div>

@push('scripts')
    <script>
        document.addEventListener('DOMContentLoaded', () => {

            const input = document.getElementById('amountInput');
            if (!input) return;

            input.addEventListener('input', function () {

                let cursor = this.selectionStart; // save cursor position
                let originalLength = this.value.length;

                console.log(this.value.replace(/,/g, ''));
                const v = this.value.replace(/,/g, '');
                // CLEAN INPUT
                let raw = v.replace(/[^0-9.]/g, '');

                // Prevent multiple dots
                let parts = raw.split('.');
                if (parts.length > 2) {
                    raw = parts[0] + '.' + parts.slice(1).join('');
                }

                let [int, dec] = raw.split('.');

                // FORMAT INTEGER
                if (int) {
                    int = int.replace(/\B(?=(\d{3})+(?!\d))/g, ',');
                }

                // LIMIT DECIMALS
                if (dec !== undefined) {
                    dec = dec.substring(0, 2);
                    this.value = int + '.' + dec;
                } else {
                    this.value = int;
                }

                // 🔥 FIX CURSOR POSITION
                let newLength = this.value.length;
                cursor = cursor + (newLength - originalLength);
                this.setSelectionRange(cursor, cursor);

                // SEND CLEAN VALUE TO LIVEWIRE
                // @this.set('amount', raw);
            });

        });
    </script>
    
    <script>
        function confirmModal() {
            return {
                open: false,
                loading: false,

                title: '',
                message: '',
                warning: '',

                amount: '',
                fee: '',
                total: '',

                requireOtp: true,
                otp: '',

                showSummary: false,

                callback: null,

                show(config) {
                    this.title = config.title || 'Confirm Action';
                    this.message = config.message || '';
                    this.warning = config.warning || '';

                    this.amount = config.amount || '';
                    this.fee = config.fee || '';
                    this.total = config.total || '';

                    this.requireOtp = config.requireOtp || false;
                    this.showSummary = config.showSummary || false;

                    this.callback = config.onConfirm || null;

                    this.open = true;
                },

                close() {
                    this.open = false;
                    this.loading = false;
                    this.otp = '';
                },

                async confirm() {
                    this.loading = true;

                    if (this.callback) {
                        await this.callback(this.otp);
                    }

                    this.loading = false;
                    this.close();
                }
            }
        }
    </script>

    <script>
        function compoundModal() {
            return {
                showCompound: false, 
            }
        }
        function convertModal() {
            return {
                open: false,
                loading: false,

                from: 'profit',
                amount: '',

                openModal() {
                    this.open = true;
                },

                close() {
                    this.open = false;
                    this.amount = '';
                },

                format(value) {
                    return new Intl.NumberFormat('en-US', {
                        minimumFractionDigits: 2,
                        maximumFractionDigits: 2
                    }).format(parseFloat(value || 0));
                },

                async submit() {
                    this.loading = true;

                    const result = await @this.call('processConvert', this.from, this.amount);

                    this.loading = false;

                    console.log(result)

                    if(result) {
                        this.amount = '';
                        this.close();
                    }
                }
            }
        }
    </script>
@endpush
