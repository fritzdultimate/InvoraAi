<div class="auth-flux-panel mx-auto flex w-full max-w-md flex-col gap-5">
    
    <x-auth-header 
        :title="__('Reset password')" 
        :description="__('Enter your email and new password to reset your account')" 
    />

    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" action="{{ route('password.update') }}" class="flex flex-col gap-5">
        @csrf

        <!-- TOKEN -->
        <input type="hidden" name="token" value="{{ request()->route('token') }}">

        <!-- EMAIL -->
        <flux:input
            name="email"
            :label="__('Email Address')"
            type="email"
            required
            autofocus
            placeholder="email@example.com"
            :value="old('email', request('email'))"
        />

        <!-- PASSWORD -->
        <flux:input
            name="password"
            :label="__('New Password')"
            type="password"
            required
            placeholder="••••••••"
        />

        <!-- CONFIRM PASSWORD -->
        <flux:input
            name="password_confirmation"
            :label="__('Confirm Password')"
            type="password"
            required
            placeholder="••••••••"
        />

        <!-- SUBMIT -->
        <flux:button 
            variant="primary" 
            type="submit" 
            class="w-full"
        >
            {{ __('Reset Password') }}
        </flux:button>
    </form>

    <div class="text-center text-sm text-[#8fa3b5]">
        <span>{{ __('Back to') }}</span>
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

</div>