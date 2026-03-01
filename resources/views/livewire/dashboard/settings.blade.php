<div class="invora-settings-wrapper">

    <!-- SIDEBAR -->
    <div class="invora-settings-sidebar">

        <button wire:click="$set('tab','profile')" class="active">Profile</button>
        <button wire:click="$set('tab','security')">Security</button>
        <button wire:click="$set('tab','kyc')">KYC</button>
        <button wire:click="$set('tab','notifications')">Notifications</button>
        <button wire:click="$set('tab','sessions')">Sessions</button>

    </div>

    <!-- CONTENT -->
    <div class="invora-settings-content">

        @if($tab === 'profile')
            @include('livewire.dashboard.profile')
        @endif

        @if($tab === 'security')
            @include('settings.security')
        @endif

        @if($tab === 'kyc')
            @include('settings.kyc')
        @endif

        @if($tab === 'notifications')
            @include('settings.notifications')
        @endif

        @if($tab === 'sessions')
            @include('settings.sessions')
        @endif

    </div>

</div>