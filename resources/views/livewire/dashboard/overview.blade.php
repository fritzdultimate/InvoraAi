<div class="flex flex-col gap-6">
    <div class="grid grid-cols-1 xl:grid-cols-12 gap-6">

        <div class="invora-grid col-span-12 2xl:col-span-6">

            <!-- LICENSE SIDE -->
            <div class="invora-license hidden">

                @if($has_active_license)
                    <h6>Bot Active ✅</h6>
                    <p>Your system is actively generating returns.</p>

                    <div class="invora-mini-value" style="margin-top:10px;">
                        Expires: {{ $license_expires_at?->diffForHumans() }}
                    </div>

                    <a href="{{ route('bot') }}" class="invora-license-btn" style="margin-top:10px;">
                        View Bot Activity
                    </a>
                @else
                    <h6>Activate Bot</h6>
                    <p>Put your capital to work automatically.</p>

                    <a href="{{ route('bot') }}" class="invora-license-btn" style="margin-top:10px;">
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

                        <a href="{{ route('deposit') }}" class="invora-action-btn deposit">
                            <!-- <iconify-icon icon="solar:card-send-bold"></iconify-icon> -->
                            <iconify-icon icon="mdi:plus"></iconify-icon>
                            <span>Deposit</span>
                        </a>

                        <a href="{{ route('withdrawal') }}" class="invora-action-btn withdraw">
                            <iconify-icon icon="ph:coins-bold"></iconify-icon>
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