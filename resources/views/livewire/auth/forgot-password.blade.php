<div class="elementor-element elementor-element-d054b34 e-flex e-con-boxed e-con e-parent">
    <div class="e-con-inner">
        <div class="elementor-element elementor-element-a1c12d4 e-con-full e-flex e-con e-child">

            <div class="elementor-widget elementor-widget-heading">
                <div class="elementor-widget-container">
                    <h2 class="elementor-heading-title elementor-size-default">
                        Forgot Your Password?
                    </h2>
                </div>
            </div>

            <div class="elementor-widget elementor-widget-heading">
                <div class="elementor-widget-container">
                    <div class="elementor-heading-title elementor-size-default">
                        Enter your registered email address and we’ll send you a secure link to reset your password.
                    </div>
                </div>
            </div>

            <div class="elementor-widget-divider">
                <div class="elementor-divider">
                    <span class="elementor-divider-separator"></span>
                </div>
            </div>

            <div class="elementor-element elementor-element-649ce4e elementor-button-align-stretch elementor-widget elementor-widget-form"
                data-id="649ce4e" data-element_type="widget"
                data-settings="{&quot;step_next_label&quot;:&quot;Next&quot;,&quot;step_previous_label&quot;:&quot;Previous&quot;,&quot;button_width&quot;:&quot;100&quot;,&quot;step_type&quot;:&quot;number_text&quot;,&quot;step_icon_shape&quot;:&quot;circle&quot;}"
                data-widget_type="form.default">
                <div class="elementor-widget-container">
                    <form wire:submit.prevent="sendResetLink">

                        <div class="elementor-form-fields-wrapper elementor-labels-above">

                            <div
                                class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-name elementor-col-50 elementor-md-100">
                                <label for="email" class="elementor-field-label">
                                    Email
                                </label>
                                <input id="email" size="1" type="text"
                                    class="elementor-field elementor-size-sm  elementor-field-textual"
                                    placeholder="Email" wire:model.defer="email">

                                @error('email') <small class="text-red-500 text-xs">{{ $message }}</small> @enderror
                            </div>

                            <div class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100 e-form__buttons">
                                <button class="elementor-button elementor-size-sm" type="submit"
                                    wire:loading.attr="disabled">
                                    <span class="elementor-button-content-wrapper">

                                        <span wire:loading.remove>
                                            Send Reset Link
                                        </span>

                                        <span wire:loading>
                                            Sending...
                                        </span>

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
