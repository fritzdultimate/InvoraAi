<div class="deposit-container">
    <div class="deposit-card">

        <!-- Header -->
        <div class="deposit-header">
            <div class="deposit-img-wrapper">
                @if($deposit->status === \App\Enums\DepositStatus::FINISHED)
                    <img src="{{ asset('assets/images/gif/success-img3.gif') }}" class="deposit-img">
                @else
                    <img src="{{ asset('assets/images/currency/' . strtolower($network) . '.png') }}" class="deposit-img">
                @endif
            </div>
            <h4 class="deposit-title">
                @if($deposit->status === \App\Enums\DepositStatus::FINISHED)
                    Payment Completed!
                @else
                    Awaiting Payment
                @endif
            </h4>
            @if($deposit->status !== \App\Enums\DepositStatus::FINISHED)
                <p class="deposit-subtitle">Send the exact amount to the address below</p>
            @endif
        </div>

        <!-- Wallet Address -->

        <div class="deposit-address">
            @if($deposit->status === \App\Enums\DepositStatus::FINISHED)
                <p class="deposit-label">Thank You!</p>
                <p class="text-green-400 text-sm mt-1" style="color: #16a34a">
                    Your deposit has been confirmed.
                </p>
            @else
                <p class="deposit-label">Wallet Address</p>
                <div class="deposit-address-row">
                    <code class="deposit-code">{{ $invoice['pay_address'] ?? '---' }}</code>
                    <button onclick="navigator.clipboard.writeText('{{ $invoice['pay_address'] ?? '---' }}')" class="deposit-btn">
                        <i class="ri-clipboard-fill"></i>
                    </button>
                </div>
            @endif
        </div>


        <!-- Amount & Network -->
        <div class="deposit-details">
            <div class="deposit-detail">
                <p>Amount</p>
                <strong>{{ $invoice['pay_amount'] ?? 0 }} {{ strtoupper($network) }}</strong>
            </div>
            <div class="deposit-detail">
                <p>Network</p>
                <strong>{{ strtoupper($network) }}</strong>
            </div>
        </div>

        <!-- Countdown -->
        <div class="deposit-countdown">
            <p>Expires in</p>
            <p id="deposit-time">{{ $expiresAt }}</p>
        </div>

        <!-- Actions -->
        <div class="deposit-actions">
            <button wire:click="checkDepositStatus" class="deposit-pay-btn">I’ve Paid</button>
        </div>

    </div>
</div>




@push('scripts')
    <script>
document.addEventListener('DOMContentLoaded', function() {
    const depositTimeEl = document.getElementById('deposit-time');
    const expiresAt = new Date("{{ $expiresAt }}").getTime();

    function updateCountdown() {
        const now = new Date().getTime();
        const distance = expiresAt - now;

        if (distance <= 0) {
            depositTimeEl.textContent = "Expired";
            return;
        }

        const minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
        const seconds = Math.floor((distance % (1000 * 60)) / 1000);

        depositTimeEl.textContent = `${String(minutes).padStart(2,'0')}:${String(seconds).padStart(2,'0')}`;
    }

    updateCountdown();
    setInterval(updateCountdown, 1000);
});
</script>


@endpush