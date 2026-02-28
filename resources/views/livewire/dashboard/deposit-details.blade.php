<div class="invora-container" wire:poll.10s>

    <div class="invora-payment-card" 
         x-data="{
            time: 10,
            copied: false,
            expired: false
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
                <p x-show="!expired">Send the exact amount below</p>
                <p x-show="expired" class="text-red-400">Payment expired</p>
            </div>

            <div class="invora-status"
                 :class="expired ? 'failed' : 'pending'">
                <span x-text="expired ? 'Expired' : 'Waiting'"></span>
            </div>
        </div>

        <!-- COUNTDOWN -->
        <div class="invora-countdown" 
             :class="time < 60 ? 'danger' : ''"
             x-show="!expired">

            ⏳ 
            <span x-text="Math.floor(time/60) + ':' + ('0'+time%60).slice(-2)"></span>
        </div>

        <!-- AMOUNT (BIG EMPHASIS 🔥) -->
        <div class="invora-amount-box">
            <div class="label">Amount to Send</div>
            <div class="amount">$120.00</div>
        </div>

        <!-- GRID -->
        <div class="invora-payment-grid">

            <!-- LEFT -->
            <div>

                <div class="invora-field">
                    <label>Network</label>
                    <div class="value">BTC</div>
                </div>

                <!-- ADDRESS FOCUS ZONE 🔥 -->
                <div class="invora-address-box"
                     @click="
                        navigator.clipboard.writeText('1FfmbHfnpaZjKF...');
                        copied = true;
                        setTimeout(() => copied = false, 2000);
                     ">

                    <div class="label">Wallet Address</div>

                    <div class="address-text">
                        1FfmbHfnpaZjKFvyi1okTjJJusN455paPH
                    </div>

                    <div class="copy-feedback" x-show="copied">
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

            <!-- QR -->
            <div class="invora-qr-box">
                <img src="/qr.png">
            </div>

        </div>

        <!-- FOOTER -->
        <div class="invora-payment-footer">
            ⚠️ Send only BTC. Wrong network = loss of funds.
        </div>

    </div>
</div>