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
                        Sign In to Your Account
                    </h2>
                </div>
            </div>
            <div class="elementor-element elementor-element-cc6086c elementor-widget elementor-widget-heading"
                data-id="cc6086c" data-element_type="widget" data-widget_type="heading.default">
                <div class="elementor-widget-container">
                    <div class="elementor-heading-title elementor-size-default">
                        Access your dashboard, manage your investments, and stay in control of your financial journey by
                        logging into your account.
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
                    <form class="" aria-label="Login Form"
                        wire:submit.prevent="login">

                        <div class="elementor-form-fields-wrapper elementor-labels-above">
                            <div
                                class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-name elementor-col-50 elementor-md-100">
                                <label for="email" class="elementor-field-label">
                                    Email
                                </label>
                                <input
                                    id="email"
                                    size="1" 
                                    type="text"
                                    class="elementor-field elementor-size-sm  elementor-field-textual"
                                    placeholder="Name" 
                                    wire:model.defer="email"
                                >

                                @error('email') <small class="text-red-500 text-xs">{{ $message }}</small> @enderror
                            </div>


                            <div
                                x-data="{ show: false }"
                                class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-field_98da870 elementor-col-50 elementor-md-100"
                            >
                                <label for="password" class="elementor-field-label">
                                    Password
                                </label>
                                <div id="password" class="relative elementor-column">
                                    <input 
                                        size="1" 
                                        type="password"
                                        class="elementor-field elementor-size-sm  elementor-field-textual"
                                        placeholder="********" 
                                        wire:model.defer="password"
                                        :type="show ? 'text' : 'password'"
                                        autocomplete="new-password"
                                        autocorrect="off"
                                        autocapitalize="off"
                                        spellcheck="false"
                                        inputmode="text"
                                    >

                                    <button 
                                        type="button" 
                                        @click="show = !show"
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
                                        <svg x-show="show" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"x-cloak
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
                                @error('password') <div class="text-red-500 text-xs block">{{ $message }}</div> @enderror
                            </div>

                            <div
                                class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100 e-form__buttons"
                            >
                                <button class="elementor-button elementor-size-sm" type="submit">
                                    <span class="elementor-button-content-wrapper">
                                        <span class="elementor-button-icon">
                                            <i aria-hidden="true" class="icons icon-user"></i> </span>
                                        <span class="elementor-button-text">Login</span>
                                    </span>
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>