<div class="invora-security-wrapper">

    <!-- 🔐 HEADER -->
    <div class="invora-security-header">
        <div>
            <h3>Security Center</h3>
            <p>Manage and protect your account</p>
        </div>
    </div>

    <!-- 🔒 SECURITY CARDS -->
    <div class="invora-security-grid mt-4">

        <!-- 2FA -->
        <div class="invora-security-card">
            <div class="left">
                <i class="ri-shield-keyhole-line"></i>

                <div>
                    <div class="title">Two-Factor Authentication</div>
                    <div class="sub">
                        Add an extra layer of protection
                    </div>
                </div>
            </div>

            <label class="invora-switch">
                <input type="checkbox" wire:model="twofa">
                <span class="invora-slider"></span>
            </label>
        </div>

        <!-- LOGIN ALERTS -->
        <div class="invora-security-card">
            <div class="left">
                <i class="ri-mail-lock-line"></i>

                <div>
                    <div class="title">Login Alerts</div>
                    <div class="sub">
                        Get notified of new logins
                    </div>
                </div>
            </div>

            <label class="invora-switch">
                <input type="checkbox" wire:model="login_alerts">
                <span class="invora-slider"></span>
            </label>
        </div>

    </div>

    <!-- ⚠️ DANGER ZONE -->
    <div class="invora-danger-zone mt-4">

        <div class="danger-header">
            <i class="ri-alert-line"></i>
            <span>Danger Zone</span>
        </div>

        <p>
            Actions here can affect your account security
        </p>

        <button wire:click="logoutOthers" class="invora-btn-danger">
            Logout Other Devices
        </button>

    </div>

</div>