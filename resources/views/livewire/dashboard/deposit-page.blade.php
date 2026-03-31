<div class="invora-container" wire:poll.10s>

    <div class="invora-payment-card" 
         x-data="{
            time: {{ $remainingSeconds }},
            copied: false,
            expired: false,
            status: '{{ $deposit->status }}'
         }"
         x-init="
            let interval = setInterval(() => {
                if(time > 0){
                    time--;
                } else {
                    expired = true;
                    clearInterval(interval);
                }
            }, 1000)
         ">

        <!-- HEADER -->
        <div class="invora-payment-header">
            <div>
                <h3>Complete Your Deposit</h3>
                <p x-show="!expired" x-cloak>Send the exact amount below</p>
                <p x-show="expired" class="text-red-400" x-cloak>Payment expired</p>
            </div>

            <div class="invora-status"
                 :class="expired ? 'failed' : 'pending'">
                <span class="capitalize" x-text="expired ? 'Expired' : '{{ $deposit->status }}'"></span>
            </div>
        </div>

        <!-- COUNTDOWN -->
        <div class="invora-countdown" 
             :class="time < 60 ? 'danger' : ''"
             x-show="!expired && status !== 'finished'">

            ⏳ 
            <span x-text="Math.floor(time/60) + ':' + ('0'+time%60).slice(-2)"></span>
        </div>

        <!-- AMOUNT (BIG EMPHASIS 🔥) -->
        <div class="invora-amount-box">
            <div class="label">Amount to Send</div>
            <div class="amount">${{ number_format($deposit->amount, 2) }}</div>
        </div>

        <!-- GRID -->
        <div class="invora-payment-grid">

            <!-- QR -->
            <livewire:dashboard.qr-code address="{{  $deposit->address  }}" />
            
            <!-- LEFT -->
            <div>

                <div class="invora-field">
                    <label>Currency</label>
                    <div class="value uppercase">{{ $deposit->currency }}</div>
                </div>

                <!-- ADDRESS FOCUS ZONE 🔥 -->
                <div class="invora-address-box"
                     @click="
                        navigator.clipboard.writeText('{{ $deposit->address }}');
                        copied = true;
                        setTimeout(() => copied = false, 2000);
                     ">

                    <div class="label">Wallet Address</div>

                    <div class="address-text">
                        {{ $deposit->address }}
                    </div>

                    <div class="copy-feedback" x-show="copied" x-cloak>
                        Copied ✅
                    </div>

                </div>

                <!-- ACTION BUTTONS -->
                <div class="invora-actions">

                    <button class="invora-btn-primary">
                        I’ve Sent Payment
                    </button>

                </div>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="invora-payment-footer">
            ⚠️ Send only {{ strtoupper($deposit->currency) }}. Wrong network = loss of funds.
        </div>

    </div>
</div>