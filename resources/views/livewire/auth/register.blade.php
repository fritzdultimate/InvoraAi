<div class="elementor-element elementor-element-d054b34 e-flex e-con-boxed e-con e-parent e-lazyloaded"
    data-id="d054b34" data-element_type="container">
    <div class="e-con-inner">
        <div class="elementor-element elementor-element-a1c12d4 e-con-full e-flex e-con e-child animated fadeInLeft"
            data-id="a1c12d4" data-element_type="container"
            data-settings="{&quot;animation&quot;:&quot;fadeInLeft&quot;}">


            <div class="elementor-element elementor-element-c3e1e76 elementor-widget elementor-widget-heading"
                data-id="c3e1e76" data-element_type="widget" data-widget_type="heading.default">
                <div class="elementor-widget-container">
                    <h2 class="elementor-heading-title elementor-size-default">
                        Create Your Account
                    </h2>
                </div>
            </div>
            <div class="elementor-element elementor-element-cc6086c elementor-widget elementor-widget-heading"
                data-id="cc6086c" data-element_type="widget" data-widget_type="heading.default">
                <div class="elementor-widget-container">
                    <div class="elementor-heading-title elementor-size-default">
                        Join {{ config('app.public_name') }} today to start managing your investments, track your financial growth, and take full control of your financial journey.
                    </div>
                </div>
            </div>


            <div class="elementor-element elementor-element-1c0c898 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                data-id="1c0c898" data-element_type="widget" data-widget_type="divider.default">
                <div class="elementor-widget-container">
                    <div class="elementor-divider">
                        <span class="elementor-divider-separator">
                        </span>
                    </div>
                </div>
            </div>


            <div class="elementor-element elementor-element-649ce4e elementor-button-align-stretch elementor-widget elementor-widget-form"
                data-id="649ce4e" data-element_type="widget"
                data-settings="{&quot;step_next_label&quot;:&quot;Next&quot;,&quot;step_previous_label&quot;:&quot;Previous&quot;,&quot;button_width&quot;:&quot;100&quot;,&quot;step_type&quot;:&quot;number_text&quot;,&quot;step_icon_shape&quot;:&quot;circle&quot;}"
                data-widget_type="form.default">
                <div class="elementor-widget-container">

                    <form class="" aria-label="Register Form" wire:submit.prevent="register">

                        <div class="elementor-form-fields-wrapper elementor-labels-above">
                            <div
                                class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-name elementor-col-50 elementor-md-100">
                                <label for="fullname" class="elementor-field-label">
                                    Full name
                                </label>
                                <input id="fullname" size="1" type="text"
                                    class="elementor-field elementor-size-sm  elementor-field-textual"
                                    placeholder="Name" wire:model.defer="fullname">

                                @error('fullname') <small class="text-red-500 text-xs">{{ $message }}</small> @enderror
                            </div>

                            <div
                                class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-name elementor-col-50 elementor-md-100">
                                <label for="email" class="elementor-field-label">
                                    Email
                                </label>
                                <input id="email" size="1" type="text"
                                    class="elementor-field elementor-size-sm  elementor-field-textual"
                                    placeholder="Name" wire:model.defer="email">

                                @error('email') <small class="text-red-500 text-xs">{{ $message }}</small> @enderror
                            </div>


                            <div 
                                x-data="{ show: false }"
                                class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-name elementor-col-50 elementor-md-100"
                            >
                                <label for="password" class="elementor-field-label">
                                    Password
                                </label>

                                <div class="relative w-full">
                                    <input size="1"
                                    id="password"
                                        class="elementor-field elementor-size-sm  elementor-field-textual"
                                        placeholder="********" 
                                        wire:model.defer="password"
                                        :type="show ? 'text' : 'password'" 
                                        autocomplete="new-password" 
                                        autocorrect="off"
                                        autocapitalize="off" 
                                        spellcheck="false" 
                                        inputmode="text">

                                    <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-3 flex items-center bg-transparent! hover:bg-transparent!">

                                        <!-- show -->
                                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" x-cloak
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>

                                        <!-- hide -->
                                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" x-cloak
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6.223 6.223A9.957 9.957 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.135" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3l18 18" />
                                        </svg>

                                    </button>
                                </div>
                                @error('password') <div class="text-red-500 text-xs block">{{ $message }}</div>
                                @enderror
                            </div>

                            <div 
                                x-data="{ show: false }"
                                class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-field_98da870 elementor-col-50 elementor-md-100"
                            >
                                <label for="password_confirmation" class="elementor-field-label">
                                    Confirm Password
                                </label>
                                <div class="relative w-full elementor-column">
                                    <input 
                                        id="password_confirmation"
                                        size="1"
                                        class="elementor-field elementor-size-sm  elementor-field-textual"
                                        placeholder="********" 
                                        wire:model.defer="password_confirmation"
                                        :type="show ? 'text' : 'password'" 
                                        autocomplete="new-password" 
                                        autocorrect="off"
                                        autocapitalize="off" 
                                        spellcheck="false" 
                                        inputmode="text"
                                    >

                                    <button type="button" @click="show = !show"
                                        class="absolute inset-y-0 right-3 flex items-center bg-transparent! hover:bg-transparent!">

                                        <!-- show -->
                                        <svg x-show="!show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" x-cloak
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>

                                        <!-- hide -->
                                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" x-cloak
                                            fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a9.956 9.956 0 012.042-3.368" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M6.223 6.223A9.957 9.957 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.956 9.956 0 01-4.043 5.135" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M3 3l18 18" />
                                        </svg>

                                    </button>
                                </div>
                            </div>

                            <div
                                class="elementor-field-group elementor-column elementor-col-100"
                            >
                                <label class="flex items-center gap-2 text-sm cursor-pointer">
                                    <input
                                        type="checkbox"
                                        wire:model.defer="accept_terms"
                                        class="mt-1 rounded border-gray-300 focus:ring-primary text-primary"
                                    >

                                    <span class="text-gray-600">
                                        I agree to the
                                        <a href="{{ route('terms') }}" target="_blank"
                                        class="text-primary hover:underline font-medium">
                                            Terms of Service
                                        </a>
                                        and
                                        <a href="{{ route('privacy-policy') }}" target="_blank"
                                        class="text-primary hover:underline font-medium">
                                            Privacy Policy
                                        </a>
                                    </span>
                                </label>

                                @error('accept_terms')
                                    <small class="text-red-500 text-xs block mt-1">
                                        {{ $message }}
                                    </small>
                                @enderror
                            </div>

                            <!-- Submit btn -->
                            <div
                                class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100 e-form__buttons">
                                <button class="elementor-button elementor-size-sm" type="submit"
                                    wire:loading.attr="disabled" wire:target="register">
                                    <span class="elementor-button-content-wrapper">

                                        <!-- Normal state -->
                                        <span class="elementor-button-icon" wire:loading.remove wire:target="register">
                                            <i aria-hidden="true" class="icons icon-user"></i>
                                        </span>
                                        <span class="elementor-button-text" wire:loading.remove wire:target="register">
                                            Register
                                        </span>

                                        <!-- Loading state -->
                                         <span class="elementor-button-icon" wire:loading wire:target="register">
                                            <svg class="animate-spin h-4 w-4" viewBox="0 0 24 24" fill="none">
                                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor"
                                                    stroke-width="4"></circle>
                                                <path class="opacity-75" fill="currentColor"
                                                    d="M4 12a8 8 0 018-8v4a4 4 0 00-4 4H4z"></path>
                                            </svg>
                                        </span>
                                        <span class="elementor-button-text" wire:loading wire:target="register">
                                            Creating account...
                                        </span>

                                    </span>
                                </button>
                            </div>

                            <div class="text-center">
                                <p class="text-sm text-gray-600">
                                    Already have an account?
                                    <a href="{{ route('login') }}"
                                        class="text-primary font-medium hover:underline">
                                        Sign in
                                    </a>
                                </p>
                            </div>

                        </div>
                    </form>
                    
                </div>
            </div>
        </div>
    </div>
</div>