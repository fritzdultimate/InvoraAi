<div class="" x-data="{subscribeModalOn: false}">


    <div class="gap-6 grid grid-cols-1 2xl:grid-cols-12" style="margin-bottom: 30px;">
        <div class="col-span-12 2xl:col-span-8">
            <div class="gap-6 grid grid-cols-1 sm:grid-cols-12">

                <div class="col-span-12">
                    <div class="mb-4 mt-8 flex flex-wrap justify-between gap-4">
                        <h6 class="mb-0">Available Trading Bots</h6>
                    </div>

                    <div id="default-tab-content">
                        <div style="margin-bottom: 25px;" class="rounded-lg bg-gray-50 dark:bg-gray-800" id="all"
                            role="tabpanel" aria-labelledby="all-tab">

                            <div class="invora-bot-grid">

                                @foreach($bots as $bot)

                                    <div class="invora-bot-card">

                                        <div class="bot-glow"></div>

                                        {{-- HEADER --}}
                                        <div class="bot-top">

                                            <div class="bot-avatar">
                                                <img src="{{ asset('assets/images/bot/' . $bot->slug . '.png') }}">
                                            </div>

                                            <div class="bot-title">
                                                <h3>{{ $bot->name }}</h3>
                                                <span>AI Quant Engine</span>
                                            </div>

                                            <div class="bot-roi">
                                                {{ rtrim(rtrim($bot->daily_return_percent, '0'), '.') }}%
                                                <small>Daily ROI</small>
                                            </div>

                                        </div>

                                        {{-- Mini Chart --}}
                                        <div class="bot-chart">
                                            <div id="botChart{{ $bot->id }}"></div>
                                        </div>


                                        {{-- ANALYTICS --}}
                                        <div class="bot-analytics">

                                            <div class="bot-metric">
                                                <span>License</span>
                                                <strong>${{ number_format($bot->price, 2) }}</strong>
                                            </div>

                                            <div class="bot-metric">
                                                <span>Duration</span>
                                                <strong>{{ $bot->license_duration_days }}d</strong>
                                            </div>

                                            <div class="bot-metric">
                                                <span>Capital Range</span>
                                                <strong>
                                                    ${{ number_format($bot->min_amount) }} —
                                                    ${{ number_format($bot->max_amount) }}
                                                </strong>
                                            </div>

                                        </div>


                                        {{-- PROFIT PREVIEW --}}
                                        <div class="bot-profit">

                                            <div class="profit-row">
                                                <span>Projected Profit</span>
                                                <span class="profit-green">
                                                    {{ rtrim(rtrim($bot->daily_return_percent, '0'), '.') }}% / day
                                                </span>
                                            </div>

                                            <div class="profit-bar">
                                                @php
                                                    $botCount = $bots->count();
                                                    $baseShare = $botCount > 0 ? (20 / $botCount) : 0;

                                                    $p = $bot->profitCycles()->sum('profit_amount');

                                                   $profitShare = $totalPlatformProfits > 0
                                                        ? ($bot->total_profit / $totalPlatformProfits) * 80
                                                        : 0;
                                                    $pct = round($baseShare + $profitShare, 2);
                                                @endphp
                                                <div class="profit-fill" style="width: {{ $pct }}%"></div>
                                            </div>

                                            <div class="profit-note">
                                                AI optimized strategy using automated trade execution b{{ $p }}
                                            </div>

                                        </div>


                                        {{-- CTA --}}
                                        <div class="bot-action">

                                            @if ($activeLicense && $activeLicense->bot->price < $bot->price)

                                                <button wire:click="prepareUpgrade({{ $bot['id'] }})"
                                                    wire:loading.attr="disabled" x-on:click="subscribeModalOn = true"
                                                    class="bot-btn-upgradef btn rounded-full btn-primary-600 w-full mt-3 py-2 bg-gradient-to-r from-blue-500 to-indigo-600 text-white font-semibold hover:from-indigo-600 hover:to-blue-500 transition-all duration-300 flex justify-center items-center gap-2">

                                                    <span wire:loading.remove>Upgrade Bot</span>

                                                    <span wire:loading class="btn-loader">
                                                        <span class="spinner"></span>
                                                        Processing...
                                                    </span>

                                                </button>

                                            @else

                                                @if($activeLicense && $activeLicense->bot->price >= $bot->price)

                                                    <button class="bot-btn-disabled" disabled>
                                                        Active License
                                                    </button>

                                                @else

                                                    <button wire:click="selectBot({{ $bot['id'] }})" wire:loading.attr="disabled"
                                                        x-on:click="subscribeModalOn = true" class="bot-btn-primary">

                                                        <span wire:loading.remove>Get License</span>

                                                        <span wire:loading class="btn-loader">
                                                            <span class="spinner"></span>
                                                            Processing...
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
        <div id="subscribe-modal" tabindex="-1" aria-hidden="true" class="modal-wrapper justify-center items-center flex"
            x-show="subscribeModalOn">

            <div class="modal-container">
                <div class="modal-card">

                    <!-- Header -->
                    <div class="modal-header">
                        <h5 class="modal-title">
                            Subscribe to Bot License
                        </h5>

                        <button type="button" class="modal-close-btn" data-modal-hide="subscribe-modal"
                            wire:click="resetBot" x-on:click="subscribeModalOn = false">
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

                                    <button wire:click="resetBot" x-on:click="subscribeModalOn = false" class="secondary-btn">
                                        Close
                                    </button>
                                </div>
                            </div>
                        @else

                            {{-- Asset Selector --}}
                            <div class="mt-4">
                                <label class="modal-label">Select Payment Wallet</label>

                                <select wire:model="asset" class="modal-select">

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

                            <button wire:click="subscribeToBot" wire:loading.attr="disabled" wire:target="subscribeToBot"
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
@endpush