
<div class="invora-card col-span-12 2xl:col-span-6">

    <div class="invora-header">
        <h6 class="invora-title">Cash Flow</h6>
        <span style="font-size:12px; color:var(--text-secondary)">
            Last 7 days
        </span>
    </div>

    <div style="padding: 10px 16px 20px 16px;">
        <div id="walletChart"></div>
    </div>

</div>

@push('scripts')
    <!-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> -->

    <script>
        document.addEventListener('livewire:navigated', renderChart);
        document.addEventListener('livewire:load', renderChart);

        function renderChart() {
            console.log('called');

            const data = @json($chartData);

            if (!data || !data.labels) return;

            const options = {
                chart: {
                    type: 'area',
                    height: 300,
                    background: 'transparent',
                    toolbar: { show: false },
                    animations: {
                        enabled: true,
                        easing: 'easeinout',
                        speed: 500
                    }
                },

                series: [
                    {
                        name: 'Inflow',
                        data: data.credits
                    },
                    {
                        name: 'Outflow',
                        data: data.debits
                    }
                ],

                colors: ['#22c55e', '#ef4444'],

                stroke: {
                    curve: 'smooth',
                    width: 2.5
                },

                fill: {
                    type: 'gradient',
                    gradient: {
                        shade: 'dark',
                        type: "vertical",
                        opacityFrom: 0.25,
                        opacityTo: 0.02,
                        stops: [0, 100]
                    }
                },

                /* 🔥 CLEAN X AXIS */
                xaxis: {
                    categories: data.labels,
                    tickAmount: 4, // ✅ prevents crowding
                    labels: {
                        style: {
                            colors: '#6b7280',
                            fontSize: '11px'
                        },
                        formatter: function (val) {
                            const d = new Date(val);
                            return d.toLocaleDateString(undefined, { month: 'short', day: 'numeric' });
                        }
                    },
                    axisBorder: { show: false },
                    axisTicks: { show: false }
                },  

                /* 🔥 MONEY FORMAT */
                yaxis: {
                    labels: {
                        style: {
                            colors: '#6b7280',
                            fontSize: '11px'
                        },
                        formatter: function (val) {
                            return '$' + val.toFixed(0);
                        }
                    }
                },

                tooltip: {
                    theme: 'dark',
                    // x: {
                    //     formatter: function (val) {
                    //         console.log(val)
                    //         const d = new Date(val);
                    //         return d.toDateString();
                    //     }
                    // },
                    y: {
                        formatter: function (val) {
                            return '$' + val.toFixed(2);
                        }
                    }
                },

                grid: {
                    borderColor: 'rgba(255,255,255,0.04)',
                    strokeDashArray: 3,
                    padding: {
                        left: 10,
                        right: 10
                    }
                },

                legend: {
                    position: 'top',
                    horizontalAlign: 'right',
                    labels: {
                        colors: '#9ca3af'
                    }
                },

                dataLabels: {
                    enabled: false
                },

                markers: {
                    size: 0,
                    hover: {
                        size: 5
                    }
                }
            };

            document.querySelector("#walletChart").innerHTML = "";

           let c = new ApexCharts(document.querySelector("#walletChart"), options).render();

           console.log({result: c});
        }
    </script>
@endpush