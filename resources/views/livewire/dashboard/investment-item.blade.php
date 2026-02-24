<div>

    <div class="invora-invest-page">

        @if (session()->has('success'))
            <div class="invora-toast success" id="invoraToast">
                <div class="toast-icon">✓</div>
                <div class="toast-content">
                    <div class="toast-title">Success</div>
                    <div class="toast-message">
                        {{ session('success') }}
                    </div>
                </div>
            </div>
        @endif  

        <div class="invora-hero-card">

            <div class="invora-hero-left">
                <div class="invora-hero-title">
                    {{ $investment->bot->name }}
                </div>

                <div class="invora-hero-sub">
                    Investment #{{ $investment->id }}
                </div>
            </div>

            <div class="invora-hero-right">
                <div class="invora-status-dot {{ $investment->status->value }}"></div>
                <span class="invora-status-text">
                    {{ ucfirst($investment->status->value) }}
                </span>
            </div>

            <div class="invora-hero-balance">
                <h1>
                    ${{ number_format($investment->amount + $investment->total_profit, 2) }}
                </h1>

                <div class="invora-hero-profit">
                    +${{ number_format($investment->total_profit, 2) }}
                </div>
            </div>

            <div class="invora-hero-meta">
                <div>
                    Next payout
                    <strong>{{ now()->diffForHumans($investment->next_cycle_at, true) }}</strong>
                </div>

                <div>
                    ROI
                    <strong>
                        {{ number_format(($investment->total_profit / max($investment->amount,1)) * 100, 2) }}%
                    </strong>
                </div>
            </div>

        </div>

        <!-- TOP CARDS -->
        <div class="invora-invest-top-grid">

            <div class="invora-stat-card">
                <span>Capital</span>
                <h3>${{ number_format($investment->amount, 2) }}</h3>
            </div>

            <div class="invora-stat-card">
                <span>Total Profit</span>
                <h3 class="invora-credit">
                    ${{ number_format($investment->total_profit, 2) }}
                </h3>
            </div>

            <div class="invora-stat-card">
                <span>Status</span>
                <h3 class="{{ $investment->status->value }} capitalize">
                    {{ $investment->status->value }}
                </h3>
            </div>

            <div class="invora-stat-card">
                <span>Matures</span>
                <h3 style="font-size: 15px;">
                    {{ $investment->matures_at->diffForHumans() }}
                </h3>
            </div>

        </div>

        <!-- PROGRESS -->
        @php
            $total = $investment->started_at->diffInSeconds($investment->matures_at);
            $used = $investment->started_at->diffInSeconds(now());
            $progress = $total > 0 ? min(100, ($used / $total) * 100) : 0;
        @endphp

        <div class="invora-progress-block mt-4">
            <div class="progress-label">
                Progress • {{ round($progress) }}%
            </div>
            <div class="progress-bar">
                <div class="progress-fill" style="width: {{ $progress }}%"></div>
            </div>
        </div>

        <!-- CHART -->
        <div class="invora-chart-card mt-4">
            <div id="investmentChart"></div>
        </div>

        <!-- ACTION -->
        @if(!$investment->is_early_terminated && !$investment->isMatured())
            <button 
                wire:click="confirmTerminate" 
                class="invora-danger"
                wire:loading.attr="disabled"
                wire:target="confirmTerminate"
            >
                <span wire:loading.remove wire:target="confirmTerminate">
                    Early Terminate
                </span>

                <span wire:loading wire:target="confirmTerminate" class="btn-loader">
                    <span class="spinner"></span>
                    Processing...
                </span>
            </button>
        @endif

        @if($confirmingTerminate)
            <div class="invora-confirm">

                <div class="confirm-box" style="background-color: var(--bg-card)">
                    <h4 style="font-size: 20px; color: var(--text-primary)">Terminate Investment?</h4>

                    <p style="font-size: 14px; color: var(--text-secondary)">
                        You will lose a penalty fee. This action cannot be undone.
                    </p>

                    <div class="confirm-actions">
                        <button wire:click="$set('confirmingTerminate', false)" style="font-size: 14px;">
                            Cancel
                        </button>

                        <button 
                            wire:click="terminateInvestmentConfirmed" 
                            class="danger text-gray-600" 
                            style="font-size: 14px;"
                            wire:loading.attr="disabled"
                            wire:target="terminateInvestmentConfirmed"
                        >
                            <span wire:loading.remove wire:target="terminateInvestmentConfirmed">
                                Yes, Terminate
                            </span>

                            <span wire:loading wire:target="terminateInvestmentConfirmed" class="btn-loader">
                                <span class="spinner"></span>
                            </span>
                        </button>
                    </div>

                </div>

            </div>
        @endif

    </div>
</div>

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script>
    <script>
        document.addEventListener('livewire:load', () => {
            renderInvestmentChart();
        });
        document.addEventListener('livewire:navigated', renderInvestmentChart);

        function renderInvestmentChart() {
            const data = @json($investment?->cycles ?? []);

            if (!data || data.length === 0) {
                document.querySelector("#investmentChart").innerHTML =
                    `<div class="invora-empty-chart">No profit data yet</div>`;
                return;
            }

            const profits = data.map(c => parseFloat(c.profit_amount));
            const dates = data.map(c => {
                const d = new Date(c.cycle_at);
                return d.toLocaleDateString('en-US', {month: 'short', day: 'numeric' });
            });

            const options = {
                chart: {
                    type: 'area',
                    height: 280,
                    toolbar: { show: false },
                    zoom: { enabled: false }
                },

                series: [{
                    name: 'Profit',
                    data: profits
                }],

                xaxis: {
                    categories: dates,
                    labels: {
                        style: { colors: '#9ca3af' }
                    }
                },

                yaxis: {
                    labels: {
                        formatter: val => '$' + val.toFixed(2),
                        style: { colors: '#9ca3af' }
                    }
                },

                grid: {
                    borderColor: 'rgba(255,255,255,0.03)', // softer lines
                    strokeDashArray: 4
                },

                stroke: {
                    curve: 'smooth',
                    width: 3
                },

                fill: {
                    type: 'gradient',
                    gradient: {
                        shadeIntensity: 0.3,
                        opacityFrom: 0.4,
                        opacityTo: 0.02
                    }
                },

                tooltip: {
                    theme: 'dark',
                    x: {
                        formatter: function (val) {
                            // console.log(val)
                            const d = new Date(val);
                            return d.toDateString();
                        }
                    },
                    // y: val => '$' + val.toFixed(2)
                },

                colors: ['#22c55e']
            };

            document.querySelector("#investmentChart").innerHTML = "";
            new ApexCharts(document.querySelector("#investmentChart"), options).render();
        }   
    </script>
@endpush