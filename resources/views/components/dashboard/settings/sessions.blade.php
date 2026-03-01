<div class="invora-card">

    <div class="invora-card-header">
        <i class="ri-device-line"></i> Active Sessions
    </div>

    <div class="invora-sessions-list">

        @foreach($sessions as $session)
            <div class="invora-session-item">

                <div>
                    <div class="title">
                        {{ $session['device'] }}
                    </div>
                    <div class="sub">
                        {{ $session['ip'] }} • {{ $session['last_active'] }}
                    </div>
                </div>

                @if(!$session['current'])
                    <button wire:click="logoutSession('{{ $session['id'] }}')">
                        Logout
                    </button>
                @else
                    <span class="current">This Device</span>
                @endif

            </div>
        @endforeach

    </div>

    <button wire:click="logoutOthers" class="invora-btn-secondary mt-4">
        Logout Other Devices
    </button>

</div>