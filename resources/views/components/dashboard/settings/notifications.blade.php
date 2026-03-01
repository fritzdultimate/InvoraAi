<div class="invora-card">

    <div class="invora-card-header">
        <i class="ri-notification-3-line"></i> Notifications
    </div>

    <div class="invora-settings-list">

        <div class="invora-toggle-row">
            <div>
                <div class="title">Email Notifications</div>
                <div class="sub">Receive important updates via email</div>
            </div>
            <input type="checkbox" wire:model="email_notifications">
        </div>

        <div class="invora-toggle-row">
            <div>
                <div class="title">Deposit Alerts</div>
                <div class="sub">Notify when funds are received</div>
            </div>
            <input type="checkbox" wire:model="deposit_alerts">
        </div>

        <div class="invora-toggle-row">
            <div>
                <div class="title">Withdrawal Alerts</div>
                <div class="sub">Notify on withdrawals</div>
            </div>
            <input type="checkbox" wire:model="withdrawal_alerts">
        </div>

        <div class="invora-toggle-row">
            <div>
                <div class="title">Security Alerts</div>
                <div class="sub">Login and password changes</div>
            </div>
            <input type="checkbox" wire:model="security_alerts">
        </div>

    </div>

    <button class="invora-btn-primary mt-4" wire:click="saveNotifications">
        Save Preferences
    </button>

</div>