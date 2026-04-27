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

        .invora-suspended-banner {
            background: linear-gradient(135deg, rgba(220, 38, 38, 0.12), rgba(2, 6, 23, 0.95));
            border: 1px solid rgba(220, 38, 38, 0.25);
            border-radius: 16px;
            padding: 16px;
            /* margin-bottom: 5px; */
            backdrop-filter: blur(10px);
        }

        /* MAIN LAYOUT */
        .invora-suspended-inner {
            display: flex;
            align-items: flex-start;
            gap: 14px;
        }

        /* ICON */
        .invora-suspended-icon {
            font-size: 26px;
            color: #ef4444;
            flex-shrink: 0;
            margin-top: 2px;
        }

        /* TEXT BLOCK */
        .invora-suspended-content {
            flex: 1;
        }

        .invora-suspended-content h4 {
            margin: 0;
            font-size: 15px;
            color: #fff;
            font-weight: 600;
        }

        .invora-suspended-content p {
            margin: 6px 0 0;
            font-size: 13px;
            color: #94a3b8;
            line-height: 1.6;
            max-width: 600px;
        }

        /* BUTTON (desktop) */
        .invora-suspended-action {
            flex-shrink: 0;
        }

        .invora-suspended-action a {
            display: inline-block;
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
            padding: 9px 14px;
            border-radius: 10px;
            font-size: 12px;
            text-decoration: none;
            transition: 0.2s ease;
            white-space: nowrap;
        }

        .invora-suspended-action a:hover {
            transform: translateY(-1px);
            opacity: 0.9;
        }

        /* MOBILE BUTTON (hidden on desktop) */
        .invora-suspended-mobile-action {
            display: none;
            margin-top: 10px;
        }

        .invora-suspended-mobile-action a {
            display: inline-block;
            background: linear-gradient(135deg, #dc2626, #ef4444);
            color: white;
            padding: 10px 14px;
            border-radius: 10px;
            font-size: 13px;
            text-decoration: none;
        }

        /* 📱 RESPONSIVE */
        @media (max-width: 768px) {
            .invora-suspended-inner {
                flex-direction: column;
            }

            .invora-suspended-action {
                display: none;
                /* hide desktop button */
            }

            .invora-suspended-mobile-action {
                display: block;
            }

            .invora-suspended-content p {
                max-width: 100%;
            }
        }
    </style>
@endpush
<div class="flex flex-col gap-6">
    @if(auth()->user()->suspended_at)
        <div class="invora-suspended-banner">

            <div class="invora-suspended-inner">

                <!-- ICON -->
                <div class="invora-suspended-icon">
                    <iconify-icon icon="mdi:shield-alert-outline"></iconify-icon>
                </div>

                <!-- TEXT -->
                <div class="invora-suspended-content">
                    <h4>Account Suspended</h4>
                    <p>
                        Your account has been temporarily suspended due to a policy review.
                        If you believe this was an error, please contact support for assistance.
                    </p>

                    <!-- MOBILE BUTTON (inside text block) -->
                    <div class="invora-suspended-mobile-action">
                        <a href="mailto:support@invora.ai" onclick="openSupportChat(event)">
                            Contact Support
                        </a>
                    </div>
                </div>

                <!-- DESKTOP BUTTON -->
                <div class="invora-suspended-action">
                    <a href="mailto:support@invora.ai" class="invora-suspended-btn" onclick="openSupportChat(event)">
                        Contact Support
                    </a>
                </div>

            </div>

        </div>
    @endif
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

                <a href="{{ route('live-trading') }}" class="invora-license-btn live-trading-btn">
                    <iconify-icon icon="mdi:finance"></iconify-icon>
                    Live Trading
                </a>

            </div>

            <!-- BALANCE SIDE -->
            <div>

                <!-- MAIN BALANCE -->

                <div class="invora-balance-card" x-data="{showCompound: false}">

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

                        <button class="invora-action-btn compound" @click="showCompound = !showCompound" id="compound">

                            <iconify-icon icon="mdi:trending-up"></iconify-icon>
                            <span>Compound</span>
                        </button>

                    </div>

                    <div>
                        <div x-show="showCompound" x-transition class="invora-compound-select mt-3" x-cloak>

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

                                    <a href="{{ route('investments.item', $inv->uuid) }}#compound"
                                        class="compound-invest-card {{ $inv->is_best ? 'best' : '' }}">
                                        <div>
                                            <h5>
                                                {{ $inv->bot->name }}

                                                @if($inv->is_best)
                                                    <span class="badge">Recommended</span>
                                                @endif
                                            </h5>

                                            <p>#{{ $inv->code }}</p>
                                        </div>

                                        <div class="text-right hidden">
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
                        <div class="invora-mini-title">Withdrawable</div>
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
                    doneLabel: 'Start Earning 🚀',

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
                            intro: "Deposit funds here to start trading."
                        },
                        {
                            element: document.querySelector('#investment'),
                            title: "Trade with AI BOTs",
                            intro: "Use your balance to create your first AI trade."
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
