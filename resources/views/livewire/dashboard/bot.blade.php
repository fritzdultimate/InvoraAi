<div class="" x-data="{subscribeModalOn: false}">


    <div class="gap-6 grid grid-cols-1 2xl:grid-cols-12" style="margin-bottom: 30px;">
        <div class="col-span-12 2xl:col-span-8">
            <div class="gap-6 grid grid-cols-1 sm:grid-cols-12">

                <div class="col-span-12">
                    <div class="mb-4 mt-8 flex flex-wrap justify-between gap-4">
                        <h6 class="mb-0">Available Trading Bots</h6>
                    </div>

                    <div id="default-tab-content">
                        <div style="margin-bottom: 25px;" class="rounded-lg bg-gray-50 dark:bg-gray-800" id="all" role="tabpanel"
                            aria-labelledby="all-tab">

                            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
                            @foreach($bots as $bot)
                                <div class="relative bg-linear-to-b from-gray-900 to-gray-800 rounded-2xl shadow-xl overflow-hidden hover:scale-105 transition-transform duration-300 border border-[#565656]" style="border-color: #565656">
                                
                                <!-- Bot Image -->
                                <div class="relative">
                                    <img src="{{ asset('assets/images/bot/bot-' . ($loop->index + 1) . '.jpg') }}" alt="Bot Image" class="w-full h-48 object-cover">
                                    <div class="absolute top-3 left-3 bg-blue-600 text-white text-xs px-2 py-1 rounded-full font-semibold">
                                    Min ${{ number_format($bot->min_amount,2) }} • Max ${{ number_format($bot->max_amount,2) }}
                                    </div>
                                </div>

                                <!-- Stats -->
                                <div class="p-4 space-y-3">
                                    <h3 class="text-lg font-bold text-white">{{ $bot->name }}</h3>

                                    <div class="flex flex-wrap gap-2">
                                    <div class="bg-gray-700 text-gray-200 px-2 py-1 rounded-full text-xs font-medium">Duration: {{ $bot->license_duration_days }} Days</div>
                                    <div class="bg-gray-700 text-green-400 px-2 py-1 rounded-full text-xs font-medium">ROI: {{ rtrim(rtrim($bot->daily_return_percent,'0'),'.') }}% Daily</div>
                                    </div>

                                    <!-- Action Button -->
                                    @if ($activeLicense && $activeLicense->bot->price < $bot->price)
                                        <button 
                                            wire:click="prepareUpgrade({{ $bot['id'] }})"
                                            wire:loading.attr="disabled"
                                            data-modal-target="subscribe-modal" 
                                            data-modal-toggle="subscribe-modal"
                                            x-on:click="subscribeModalOn = true"
                                            class="btn rounded-full btn-primary-600 w-full mt-3 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold hover:from-indigo-600 hover:to-blue-500 transition-all duration-300 flex justify-center items-center gap-2">
                                            
                                            <span wire:loading.remove>Upgrade</span>
                                            <span wire:loading class="flex items-center gap-2">
                                                <span class="spinner border-t-2 border-white w-4 h-4 rounded-full animate-spin"></span> Please wait...
                                            </span>
                                        </button>
                                    @else
                                        @if($activeLicense && $activeLicense->bot->price >= $bot->price)
                                            <button class="btn-disabled" disabled>Get License</button>
                                        @else
                                            <button 
                                                wire:click="selectBot({{ $bot['id'] }})"
                                                wire:loading.attr="disabled"
                                                data-modal-target="subscribe-modal" 
                                                data-modal-toggle="subscribe-modal"
                                                x-on:click="subscribeModalOn = true"
                                                class="btn rounded-full btn-primary-600 w-full mt-3 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold hover:from-indigo-600 hover:to-blue-500 transition-all duration-300 flex justify-center items-center gap-2">
                                                
                                                <span wire:loading.remove>Get License</span>
                                                <span wire:loading class="flex items-center gap-2">
                                                    <span class="spinner border-t-2 border-white w-4 h-4 rounded-full animate-spin"></span> Please wait...
                                                </span>
                                            </button>
                                        @endif
                                    @endif
                                </div>
                                </div>
                            @endforeach
                            </div>

                        </div>
                    </div>
                </div>

                

                
            </div>
        </div>

    </div>

    <x-dashboard.license-history :licenses="$licenses" />

    @if($selectedBot)
        <!-- Subscribe Modal -->
        <div id="subscribe-modal"
            tabindex="-1"
            aria-hidden="true"
            class="modal-wrapper justify-center items-center flex"
            x-show="subscribeModalOn"
        >

            <div class="modal-container">
                <div class="modal-card">

                    <!-- Header -->
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Subscribe to Bot License
                        </h5>

                        <button type="button"
                                class="modal-close-btn"
                                data-modal-hide="subscribe-modal"
                                wire:click="resetBot"
                                x-on:click="subscribeModalOn = false"
                            >
                            ✕
                        </button>
                    </div>

                    <!-- Body -->
                    <!-- <div class="modal-body"> -->

                        <div class="bot-card">

                            <h6 class="bot-title">
                                {{ $selectedBot->name ?? 'Bot' }}
                            </h6>

                            <div class="bot-details">
                                <p>License Price:
                                    <span class="highlight">
                                        ${{ number_format($selectedBot->price ?? 0, 2) }}
                                    </span>
                                </p>
                                <p>Duration:
                                    <span>{{ $selectedBot->license_duration_days ?? '--' }} days</span>
                                </p>
                                <p>Min Investment:
                                    <span>${{ number_format($selectedBot->min_amount ?? 0, 2) }}</span>
                                </p>
                                <p>Max Investment:
                                    <span>${{ number_format($selectedBot->max_amount ?? 0, 2) }}</span>
                                </p>
                            </div>

                            {{-- Success State --}}
                            @if($showSuccess)
                                <div class="license-success-box mt-4">

                                    <div class="success-icon">✓</div>

                                    <h6 class="success-title">License Activated Successfully</h6>

                                    <p class="success-text">
                                        Your trading engine is now ready.
                                        You can now create an investment under this bot.
                                    </p>

                                    <div class="flex flex-col gap-3 mt-4">
                                        <a href="{{ route('investments.create', $createdLicenseId) }}"
                                        class="subscribe-btn flex-1 text-center">
                                            Create Investment
                                        </a>

                                        <button
                                            wire:click="resetBot"
                                            x-on:click="subscribeModalOn = false"
                                            class="secondary-btn">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            @else

                                {{-- Asset Selector --}}
                                <div class="mt-4">
                                    <label class="modal-label">Select Payment Wallet</label>

                                    <select
                                        wire:model="asset"
                                        class="modal-select">

                                        <option value="main">Main Balance</option>
                                        <option value="deposit">Deposit Balance</option>
                                    </select>

                                    @error('asset')
                                        <span class="form-error">{{ $message }}</span>
                                    @enderror
                                </div>

                                @error('general')
                                    <div class="form-error mt-3">
                                        {{ $message }}
                                    </div>
                                @enderror

                                <button
                                    wire:click="subscribeToBot"
                                    wire:loading.attr="disabled"
                                    wire:target="subscribeToBot"
                                    class="subscribe-btn mt-4">

                                    <span wire:loading.remove wire:target="subscribeToBot">
                                        Subscribe Now
                                    </span>

                                    <span wire:loading wire:target="subscribeToBot" class="btn-loader">
                                        <span class="spinner"></span>
                                        Processing...
                                    </span>
                                </button>

                            @endif

                        </div>
                    <!-- </div> -->

                </div>
            </div>
        </div>
    @endif



</div>

@push('scripts')
    <script>
        // ===================== Average Enrollment Rate Start =============================== 
        function createChartTwo(chartId, color1, color2) {
            var options = {
                series: [{
                    name: 'series2',
                    data: [20000, 45000, 30000, 50000, 32000, 40000, 30000, 42000, 28000, 34000, 38000, 26000]
                }],
                legend: {
                    show: false
                },
                chart: {
                    type: 'area',
                    width: '100%',
                    height: 240,
                    toolbar: {
                        show: false
                    },
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'straight',
                    width: 3,
                    colors: [color1], // Use two colors for the lines
                    lineCap: 'round'
                },
                grid: {
                    show: true,
                    borderColor: '#D1D5DB',
                    strokeDashArray: 1,
                    position: 'back',
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: true
                        }
                    },
                    row: {
                        colors: undefined,
                        opacity: 0.5
                    },
                    column: {
                        colors: undefined,
                        opacity: 0.5
                    },
                    padding: {
                        top: -20,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                },
                fill: {
                    type: 'gradient',
                    colors: [color1], // Use two colors for the gradient
                    // gradient: {
                    //     shade: 'light',
                    //     type: 'vertical',
                    //     shadeIntensity: 0.5,
                    //     gradientToColors: [`${color1}`, `${color2}00`], // Bottom gradient colors with transparency
                    //     inverseColors: false,
                    //     opacityFrom: .6,
                    //     opacityTo: 0.3,
                    //     stops: [0, 100],
                    // },
                    gradient: {
                        shade: 'light',
                        type: 'vertical',
                        shadeIntensity: 0.5,
                        gradientToColors: [undefined, `${color2}00`], // Apply transparency to both colors
                        inverseColors: false,
                        opacityFrom: [0.4, 0.4], // Starting opacity for both colors
                        opacityTo: [0.1, 0.1], // Ending opacity for both colors
                        stops: [0, 100],
                    },
                },
                markers: {
                    colors: [color1], // Use two colors for the markers
                    strokeWidth: 3,
                    size: 0,
                    hover: {
                        size: 10
                    }
                },
                xaxis: {
                    labels: {
                        show: false
                    },
                    categories: ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'],
                    tooltip: {
                        enabled: false
                    },
                    labels: {
                        formatter: function (value) {
                            return value;
                        },
                        style: {
                            fontSize: "12px"
                        }
                    }
                },
                yaxis: {
                    labels: {
                        // formatter: function (value) {
                        //     return "$" + value + "k";
                        // },
                        style: {
                            fontSize: "12px"
                        }
                    },
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    }
                }
            };

            var chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
            chart.render();
        }

        createChartTwo('enrollmentChart', '#487FFF');
        // ===================== Average Enrollment Rate End =============================== 


        // ===================== Delete Table Item Start =============================== 
        $('.remove-btn').on('click', function () {
            $(this).closest('tr').addClass('hidden');
        });
        // ===================== Delete Table Item End =============================== 


        // ================================ Area chart Start ================================ 
        function createChart(chartId, chartColor) {

            let currentYear = new Date().getFullYear();

            var options = {
                series: [
                    {
                        name: 'series1',
                        data: [0, 10, 8, 25, 15, 26, 13, 35, 15, 39, 16, 46, 42],
                    },
                ],
                chart: {
                    type: 'area',
                    width: 164,
                    height: 72,

                    sparkline: {
                        enabled: true // Remove whitespace
                    },

                    toolbar: {
                        show: false
                    },
                    padding: {
                        left: 0,
                        right: 0,
                        top: 0,
                        bottom: 0
                    }
                },
                dataLabels: {
                    enabled: false
                },
                stroke: {
                    curve: 'smooth',
                    width: 2,
                    colors: [chartColor],
                    lineCap: 'round'
                },
                grid: {
                    show: true,
                    borderColor: 'transparent',
                    strokeDashArray: 0,
                    position: 'back',
                    xaxis: {
                        lines: {
                            show: false
                        }
                    },
                    yaxis: {
                        lines: {
                            show: false
                        }
                    },
                    row: {
                        colors: undefined,
                        opacity: 0.5
                    },
                    column: {
                        colors: undefined,
                        opacity: 0.5
                    },
                    padding: {
                        top: -3,
                        right: 0,
                        bottom: 0,
                        left: 0
                    },
                },
                fill: {
                    type: 'gradient',
                    colors: [chartColor], // Set the starting color (top color) here
                    gradient: {
                        shade: 'light', // Gradient shading type
                        type: 'vertical',  // Gradient direction (vertical)
                        shadeIntensity: 0.5, // Intensity of the gradient shading
                        gradientToColors: [`${chartColor}00`], // Bottom gradient color (with transparency)
                        inverseColors: false, // Do not invert colors
                        opacityFrom: .8, // Starting opacity
                        opacityTo: 0.3,  // Ending opacity
                        stops: [0, 100],
                    },
                },
                // Customize the circle marker color on hover
                markers: {
                    colors: [chartColor],
                    strokeWidth: 2,
                    size: 0,
                    hover: {
                        size: 8
                    }
                },
                xaxis: {
                    labels: {
                        show: false
                    },
                    categories: [`Jan ${currentYear}`, `Feb ${currentYear}`, `Mar ${currentYear}`, `Apr ${currentYear}`, `May ${currentYear}`, `Jun ${currentYear}`, `Jul ${currentYear}`, `Aug ${currentYear}`, `Sep ${currentYear}`, `Oct ${currentYear}`, `Nov ${currentYear}`, `Dec ${currentYear}`],
                    tooltip: {
                        enabled: false,
                    },
                },
                yaxis: {
                    labels: {
                        show: false
                    }
                },
                tooltip: {
                    x: {
                        format: 'dd/MM/yy HH:mm'
                    },
                },
            };

            var chart = new ApexCharts(document.querySelector(`#${chartId}`), options);
            chart.render();
        }

        // Call the function for each chart with the desired ID and color
        createChart('areaChart', '#FF9F29');
        // ================================ Area chart End ================================ 


        // ================================ Bar chart Start ================================ 
        var options = {
            series: [{
                name: "Sales",
                data: [{
                    x: 'Mon',
                    y: 20,
                }, {
                    x: 'Tue',
                    y: 40,
                }, {
                    x: 'Wed',
                    y: 20,
                }, {
                    x: 'Thur',
                    y: 30,
                }, {
                    x: 'Fri',
                    y: 40,
                }, {
                    x: 'Sat',
                    y: 35,
                }]
            }],
            chart: {
                type: 'bar',
                width: 164,
                height: 80,
                sparkline: {
                    enabled: true // Remove whitespace
                },
                toolbar: {
                    show: false
                }
            },
            plotOptions: {
                bar: {
                    borderRadius: 6,
                    horizontal: false,
                    columnWidth: 14,
                }
            },
            dataLabels: {
                enabled: false
            },
            states: {
                hover: {
                    filter: {
                        type: 'none'
                    }
                }
            },
            fill: {
                type: 'gradient',
                colors: ['#E3E6E9'], // Set the starting color (top color) here
                gradient: {
                    shade: 'light', // Gradient shading type
                    type: 'vertical',  // Gradient direction (vertical)
                    shadeIntensity: 0.5, // Intensity of the gradient shading
                    gradientToColors: ['#E3E6E9'], // Bottom gradient color (with transparency)
                    inverseColors: false, // Do not invert colors
                    opacityFrom: 1, // Starting opacity
                    opacityTo: 1,  // Ending opacity
                    stops: [0, 100],
                },
            },
            grid: {
                show: false,
                borderColor: '#D1D5DB',
                strokeDashArray: 1, // Use a number for dashed style
                position: 'back',
            },
            xaxis: {
                labels: {
                    show: false // Hide y-axis labels
                },
                type: 'category',
                categories: ['Mon', 'Tue', 'Wed', 'Thur', 'Fri', 'Sat']
            },
            yaxis: {
                labels: {
                    show: false,
                    formatter: function (value) {
                        return (value / 1000).toFixed(0) + 'k';
                    }
                }
            },
            tooltip: {
                y: {
                    formatter: function (value) {
                        return value / 1000 + 'k';
                    }
                }
            }
        };

        var chart = new ApexCharts(document.querySelector("#dailyIconBarChart"), options);
        chart.render();
        // ================================ Bar chart End ================================ 


        // ================================ Follow Btn Start ================================ 
        $('.follow-btn').on('click', function () {

            if ($(this).text() === 'Follow') {
                $(this).text('Unfollow');
            } else {
                $(this).text('Follow');
            }
        });
        // ================================ Follow Btn End ================================ 

    </script>

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