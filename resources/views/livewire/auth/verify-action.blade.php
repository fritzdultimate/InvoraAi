<div class="auth-flux-panel mx-auto flex w-full max-w-md flex-col gap-5 text-center">

    @if($success)

        <!-- ✅ SUCCESS -->
        <x-auth-header 
            :title="__('Verification Successful')" 
            :description="__('Your account has been verified successfully.')" 
        />

        <div class="text-4xl">✅</div>

        <p class="text-sm text-[#8fa3b5]">
            You can now access your dashboard.
        </p>

        <flux:button 
            variant="primary" 
            :href="route('dashboard')" 
            class="w-full"
        >
            Go to Dashboard
        </flux:button>

    @else

        <!-- ❌ FAILURE -->
        <x-auth-header 
            :title="__('Verification Failed')" 
            :description="__('This link may be invalid or expired.')" 
        />

        <div class="text-4xl">⚠️</div>

        <p class="text-sm text-[#8fa3b5]">
            {{ $error ?? 'We could not verify your request.' }}
        </p>

        <!-- 🔁 RESEND FORM -->
        <form wire:submit.prevent="send" class="flex flex-col gap-5 mt-4">

            @csrf

            <flux:input
                wire:model="email"
                :label="__('Email Address')"
                type="email"
                required
                placeholder="email@example.com"
            />

            @error('email') 
                <div class="text-red-500 text-xs">
                    {{ $message }}
                </div> 
            @enderror

            <flux:button variant="primary" type="submit" class="w-full">
                {{ __('Resend Verification Link') }}
            </flux:button>
        </form>

        <!-- STATUS MESSAGE -->
        <x-auth-session-status class="text-center" :status="session('status')" />

        <div class="text-center text-sm text-[#8fa3b5]">
            <span>{{ __('Or, return to') }}</span>
            <flux:link 
                :href="route('login')" 
                wire:navigate 
                variant="subtle" 
                :accent="false" 
                class="auth-flux-footer-link ms-1 inline font-medium"
            >
                {{ __('log in') }}
            </flux:link>
        </div>

    @endif

</div>