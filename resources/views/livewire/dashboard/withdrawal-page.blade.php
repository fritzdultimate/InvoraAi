<div class="deposit-container">
    <div class="deposit-card">

        <!-- Header -->
        <div class="deposit-header">
            <div class="deposit-img-wrapper">
                @if($withdrawal->status === \App\Enums\WithdrawalStatus::COMPLETED)
                    <img src="{{ asset('assets/images/gif/success-img3.gif') }}" class="deposit-img">
                @else
                    <img src="{{ asset('assets/images/currency/' . strtolower($withdrawal->currency->code) . '.png') }}" class="deposit-img">
                @endif
            </div>
            <h4 class="deposit-title">
                Payment Details
            </h4>
            @if($withdrawal->status !== \App\Enums\WithdrawalStatus::COMPLETED)
                <p class="deposit-subtitle">See below the progress of your withdrawal.</p>
            @endif
        </div>

        <!-- Wallet Address -->

        <div class="deposit-address">
            @if($withdrawal->status === \App\Enums\WithdrawalStatus::COMPLETED)
                <p class="deposit-label">Thank You!</p>
                <p class="text-green-400 text-sm mt-1" style="color: #16a34a">
                    Your withdrawal has been confirmed.
                </p>
            @else
                <p class="deposit-label">Wallet Address</p>
                <div class="deposit-address-row">
                    <code class="deposit-code">{{ $withdrawal->address ?? '---' }}</code>
                    <button onclick="navigator.clipboard.writeText('{{ $withdrawal->address ?? '---' }}')" class="deposit-btn">
                        <i class="ri-clipboard-fill"></i>
                    </button>
                </div>
            @endif
        </div>


        <!-- Amount & Network -->
        <div class="deposit-details">
            <div class="deposit-detail">
                <p>Amount</p>
                <strong>{{ number_format($withdrawal->amount, 2) ?? 0 }} {{ strtoupper('usd') }}</strong>
            </div>
            <div class="deposit-detail">
                <p>Currency{{ $network && '/Network' }}</p>
                <strong>
                    {{ ucfirst($withdrawal->currency->name) }}{{$network && '/' . ucfirst($network) }}
                </strong>
            </div>
        </div>

        <!-- Countdown -->
        <div class="deposit-countdown">
            <p class="status {{ $withdrawal->status->value }}">{{ ucfirst($withdrawal->status->value) }}</p>
        </div>

        <!-- Actions -->
        <div class="deposit-actions">
            <button wire:click="checkWithdrawalStatus" class="deposit-pay-btn">I’ve Received</button>
        </div>

    </div>
</div>




@push('scripts')
    <script>
</script>


@endpush