<div class="invora-card">

    <div class="invora-card-header">
        <i class="ri-device-line"></i> Active Sessions
    </div>

    <div class="invora-sessions-list">

        @foreach($sessions as $session)

            <div class="invora-session-item">

                <div class="flex items-center gap-3">
                    <i class="{{ $session['is_mobile'] ? 'ri-smartphone-line' : 'ri-computer-line' }} icon"></i>

                    <div>
                        <div class="title">
                            <!-- Chrome • Windows -->
                             {{ $session['device'] }}
                        </div>
                        <div class="sub">{{ $session['ip'] }} • {{ $session['last_active'] }}</div>
                    </div>
                </div>

                <div>
                    @if(!$session['current'])
                        <button 
                        class="device-logout text-sm" 
                        wire:click="logoutSession('{{ $session['id'] }}')"
                    >
                            <span wire:loading.remove wire:target="logoutSession">Logout</span>
                            <span wire:loading wire:target="logoutSession" class="spinner"></span>
                        </button>
                    @else
                        <span class="current">This Device</span>
                    @endif
                </div>

            </div>
        @endforeach

    </div>

    <button wire:click="logoutOthers" class="invora-btn-secondary mt-4">
        Logout Other Devices
    </button>

</div>