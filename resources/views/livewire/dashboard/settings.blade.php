<div class="invora-settings-wrapper">

    <!-- SIDEBAR -->
    <div class="invora-settings-sidebar">

        <button wire:click="$set('tab','profile')" class="{{ $tab === 'profile' ? 'active' : '' }}">Profile</button>
        <button wire:click="$set('tab','security')" class="{{ $tab === 'security' ? 'active' : '' }}">Security</button>
        <button wire:click="$set('tab','kyc')" class="{{ $tab === 'kyc' ? 'active' : '' }}">KYC</button>
        <button wire:click="$set('tab','notifications')" class="{{ $tab === 'notifications' ? 'active' : '' }}">Notifications</button>
        <button wire:click="$set('tab','sessions')" class="{{ $tab === 'sessions' ? 'active' : '' }}">Sessions</button>

    </div>

    <!-- CONTENT -->
    <div class="invora-settings-content">

        @if($tab === 'profile')
            @include('livewire.dashboard.profile')
        @endif

        @if($tab === 'security')
            @include('components.dashboard.settings.security')
        @endif

        @if($tab === 'kyc')
            @include('components.dashboard.settings.kyc')
        @endif

        @if($tab === 'notifications')
            @include('components.dashboard.settings.notifications')
        @endif

        @if($tab === 'sessions')
            @include('components.dashboard.settings.sessions')
        @endif

    </div>

</div>