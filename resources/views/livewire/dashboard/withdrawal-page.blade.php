<div class="invora-container">

    @php
        $status = $withdrawal->status;

        $map = [
            'pending' => [
                'class' => 'pending',
                'icon' => 'ri-time-line',
                'title' => 'Pending Withdrawal',
                'sub' => 'Your request is awaiting approval'
            ],
            'review' => [
                'class' => 'review',
                'icon' => 'ri-search-eye-line',
                'title' => 'Under Review',
                'sub' => 'We are verifying your withdrawal'
            ],
            'processing' => [
                'class' => 'processing',
                'icon' => 'ri-loader-4-line',
                'title' => 'Processing',
                'sub' => 'Your transaction is being processed'
            ],
            'completed' => [
                'class' => 'success',
                'icon' => 'ri-check-line',
                'title' => 'Withdrawal Successful',
                'sub' => 'Your funds have been sent successfully'
            ],
            'failed' => [
                'class' => 'failed',
                'icon' => 'ri-close-line',
                'title' => 'Transaction Failed',
                'sub' => 'Something went wrong. Try again'
            ],
            'cancelled' => [
                'class' => 'cancelled',
                'icon' => 'ri-close-circle-line',
                'title' => 'Cancelled',
                'sub' => 'This withdrawal was cancelled'
            ],
        ];

        $ui = $map[$status->value] ?? $map['pending'];
    @endphp

    <div class="invora-payment-card invora-withdrawal-card">

        <!--  STATUS HERO -->
        <div class="invora-withdrawal-hero {{ $ui['class'] }}">
            <div class="icon {{ $withdrawal->status }}">
                <i class="{{ $ui['icon'] }}"></i>
            </div>

            <div>
                <div class="title">{{ $ui['title'] }}</div>
                <div class="sub">{{ $ui['sub'] }}</div>
            </div>
        </div>

        <!--  AMOUNT -->
        <div class="invora-amount-box">
            <div class="label">Amount Sent</div>
            <div class="amount">${{ number_format($withdrawal->amount, 2) }}</div>
        </div>

        <!-- DETAILS -->
        <div class="invora-details-grid">

            <div class="invora-detail-item">
                <label>Network</label>
                <div>
                    <span class="capitalize">{{ $withdrawal->currency->name }}</span>
                     @if($withdrawal->network) 
                        <span class="uppercase">({{ $withdrawal->network->name }})</span> 
                    @endif
                </div>
            </div>

            <div class="invora-detail-item">
                <label>Status</label>
                <div class="{{ $withdrawal->status }}">{{ $withdrawal->status }}</div>
            </div>

            <div class="invora-detail-item full">
                <label>Wallet Address</label>
                <div class="copy-row">
                    {{ substr($withdrawal->address, 0, 15) }}...{{ substr($withdrawal->address, -15) }}
                    <button onclick="navigator.clipboard.writeText('{{ $withdrawal->address }}')">
                        Copy
                    </button>
                </div>
            </div>

            <div class="invora-detail-item full">
                <label>Reference</label>
                <div class="copy-row">
                    {{ substr($withdrawal->reference, 0, 15) }}...
                    <button>Copy</button>
                </div>
            </div>

            <div class="invora-detail-item">
                <label>Date</label>
                <div>{{ $withdrawal->created_at->format('M d, Y') }}</div>
            </div>

        </div>

        <!-- ACTIONS -->
        <div class="invora-actions">

            <a href="{{ route('dashboard') }}" class="invora-btn-secondary">
                Back to Dashboard
            </a>

        </div>

    </div>
</div>