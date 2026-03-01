<div>
    <div class="invora-toggle">
        <span>Two-Factor Authentication</span>
        <input type="checkbox" wire:model="twofa">
    </div>

    <div class="invora-toggle">
        <span>Email Login Alerts</span>
        <input type="checkbox" wire:model="login_alerts">
    </div>

    <button wire:click="logoutOthers" class="invora-btn-secondary">
        Logout Other Devices
    </button>
</div>