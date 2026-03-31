@push('styles')
    <style>
        /* BACKDROP */
        .introjs-overlay {
            background: rgba(2, 6, 23, 0.85) !important;
        }

        /* TOOLTIP BOX */
        .introjs-tooltip {
            background: #0f172a !important;
            color: #e2e8f0 !important;
            border-radius: 14px !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6) !important;
            padding: 18px !important;
        }

        /* TITLE */
        .introjs-tooltip-title {
            color: #ffffff !important;
            font-size: 16px !important;
            font-weight: 600 !important;
        }

        /* TEXT */
        .introjs-tooltiptext {
            color: #94a3b8 !important;
            font-size: 13px !important;
            line-height: 1.6 !important;
        }

        /* BUTTONS WRAPPER */
        .introjs-tooltipbuttons {
            border-top: 1px solid rgba(255, 255, 255, 0.05) !important;
            margin-top: 12px !important;
            padding-top: 12px !important;
        }

        /* BUTTON BASE */
        .introjs-button {
            background: #020617 !important;
            color: #e2e8f0 !important;
            border-radius: 10px !important;
            border: 1px solid rgba(255, 255, 255, 0.05) !important;
            padding: 8px 14px !important;
            font-size: 12px !important;
            transition: all 0.2s ease;
        }

        /* PRIMARY BUTTON */
        .introjs-nextbutton {
            background: linear-gradient(135deg, #009A76, #22c55e) !important;
            color: #fff !important;
            border: none !important;
        }

        /* HOVER */
        .introjs-button:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }

        /* SKIP BUTTON */
        .introjs-skipbutton {
            color: #64748b !important;
        }

        /* ARROW */
        .introjs-arrow {
            border-top-color: #0f172a !important;
        }

        /* HIGHLIGHTED ELEMENT */
        .introjs-highlight {
            border-radius: 12px !important;
            box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.4) !important;
        }
    </style>
@endpush
<div class="flex flex-col gap-6">
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

        <div class="invora-grid col-span-12 2xl:col-span-6">

            <!-- LICENSE SIDE -->
            <div class="invora-license">

                @if($has_active_license)
                    <h6>Bot Active ✅</h6>
                    <p>Your system is actively generating returns.</p>

                    <div class="invora-mini-value" style="margin-top:10px;">
                        Expires: {{ $license_expires_at?->diffForHumans() }}
                    </div>

                    <a id="license" href="{{ route('bot') }}" class="invora-license-btn" style="margin-top:10px;">
                        <iconify-icon icon="mdi:chart-line"></iconify-icon>
                        View Bot Activity
                    </a>
                @else
                    <h6>Activate Bot</h6>
                    <p>Put your capital to work automatically.</p>

                    <a id="license" href="{{ route('bot') }}" class="invora-license-btn" style="margin-top:10px;">
                        <iconify-icon icon="mdi:robot-outline"></iconify-icon>
                        Get License
                    </a>
                @endif

            </div>

            <!-- BALANCE SIDE -->
            <div>

                <!-- MAIN BALANCE -->

                <div class="invora-balance-card">

                    <div class="invora-balance-top">
                        <div>
                            <div class="invora-balance-title">Total Balance</div>

                            <div class="invora-balance-amount">
                                ${{ number_format(auth()->user()->total_balance, 2) }}
                            </div>

                            <!-- PROFIT -->
                            <div class="invora-balance-profit">
                                +${{ number_format($profit_balance, 2) }}
                            </div>
                        </div>
                    </div>

                    <!-- ACTION BUTTONS -->
                    <div class="invora-balance-actions">

                        <a href="{{ route('deposit') }}" class="invora-action-btn deposit" id="fund">
                            <!-- <iconify-icon icon="solar:card-send-bold"></iconify-icon> -->
                            <iconify-icon icon="mdi:plus"></iconify-icon>
                            <span>Deposit</span>
                        </a>

                        <a href="{{ route('withdrawal') }}" class="invora-action-btn withdraw" id="withdraw">
                            <iconify-icon icon="mdi:minus"></iconify-icon>
                            <span>Withdraw</span>
                        </a>

                    </div>

                </div>

                <!-- MINI STATS -->
                <div class="invora-mini-grid">

                    <div class="invora-mini-card">
                        <div class="invora-mini-title">Deposit Balance</div>
                        <div class="invora-mini-value">
                            ${{ number_format($deposit_balance + $deposit_bonus, 2) }}
                        </div>

                        @if ($deposit_bonus > 0)
                            <div class="invora-balance-profit">
                                +${{ number_format($deposit_bonus, 2) }} bonus
                            </div>
                        @endif
                    </div>

                    <div class="invora-mini-card">
                        <div class="invora-mini-title">Withdrable</div>
                        <div class="invora-mini-value">
                            ${{ number_format($main_balance, 2) }}
                        </div>
                    </div>

                    <div class="invora-mini-card">
                        <div class="invora-mini-title">Capital in Trade</div>
                        <div class="invora-mini-value invora-accent-strong">
                            ${{ number_format($locked_balance, 2) }}
                        </div>

                        @if($locked_balance > 0)
                            <div class="invora-mini-sub invora-credit">
                                Currently generating profit
                            </div>
                        @endif
                    </div>

                    <div class="invora-mini-card">
                        <div class="invora-mini-title">Referral Balance</div>
                        <div class="invora-mini-value invora-credit">
                            ${{ number_format($referral_balance, 2) }}
                        </div>
                    </div>

                    <div class="invora-mini-card">
                        <div class="invora-mini-title">Trades Executed</div>
                        <div class="invora-mini-value">
                            {{ number_format($total_executed_trades) }}
                        </div>
                    </div>

                    <div class="invora-mini-card">
                        <div class="invora-mini-title">Bot Status</div>
                        <div class="invora-mini-value 
                            {{ $has_active_license ? 'invora-credit' : 'invora-debit' }}">
                            {{ $has_active_license ? 'Active' : 'Inactive' }}
                        </div>
                    </div>

                </div>

            </div>

        </div>



        <x-dashboard.deposit-withdrawal-chart :chartData="$chartData" />
    </div>

    <x-dashboard.recent-transaction :transactions="$transactions" />
</div>

@if($showTour)
    @push('scripts')
        <script>
            // introJs.tour().setOptions({
            //     steps: [{
            //         intro: "Hello world!"
            //     }, {
            //         element: document.querySelector('#license'),
            //         intro: "Click here to login!"
            //     }]
            // }).start();

            document.addEventListener('DOMContentLoaded', function () {
                const tour = introJs();

                tour.setOptions({
                    nextLabel: 'Next →',
                    prevLabel: '← Back',
                    skipLabel: 'Skip',
                    doneLabel: 'Start Investing 🚀',

                    showProgress: true,
                    showBullets: false,

                    steps: [
                        {
                            title: "Welcome to Invora",
                            intro: "Let’s quickly show you how everything works."
                        },
                        {
                            element: document.querySelector('#license'),
                            title: "Get a License",
                            intro: "This is where you activate your trading bot license."
                        },
                        {
                            element: document.querySelector('#fund'),
                            title: "Fund Your Account",
                            intro: "Deposit funds here to start investing."
                        },
                        {
                            element: document.querySelector('#investment'),
                            title: "Create Investment",
                            intro: "Use your balance to create your first investment."
                        },
                        {
                            element: document.querySelector('#withdraw'),
                            title: "Withdraw Anytime",
                            intro: "Easily withdraw your profits here."
                        }
                    ]
                });
                tour.start();
                tour.oncomplete(() => @this.completeTour());
            });
        </script>
    @endpush
@endif