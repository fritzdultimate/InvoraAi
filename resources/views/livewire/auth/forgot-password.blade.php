<div class="auth-flux-panel mx-auto flex w-full max-w-md flex-col gap-5">
    <x-auth-header :title="__('Forgot password')" :description="__('Enter your email to receive a password reset link')" />

    <x-auth-session-status class="text-center" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}" class="flex flex-col gap-5">
        @csrf

        <flux:input
            name="email"
            :label="__('Email Address')"
            type="email"
            required
            autofocus
            placeholder="email@example.com"
        />

        <flux:button variant="primary" type="submit" class="w-full" data-test="email-password-reset-link-button">
            {{ __('Email password reset link') }}
        </flux:button>
    </form>

    <div class="text-center text-sm text-[#8fa3b5]">
        <span>{{ __('Or, return to') }}</span>
        <flux:link :href="route('login')" wire:navigate variant="subtle" :accent="false" class="auth-flux-footer-link ms-1 inline font-medium">{{ __('log in') }}</flux:link>
    </div>
</div>
