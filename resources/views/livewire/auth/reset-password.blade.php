<div class="elementor-element elementor-element-d054b34 e-flex e-con-boxed e-con e-parent">
    <div class="e-con-inner">
        <div class="elementor-element e-con-full e-flex e-con e-child">

            <div class="elementor-widget elementor-widget-heading">
                <h2 class="elementor-heading-title">
                    Reset Your Password
                </h2>
            </div>

            

            <div 
                class="elementor-element elementor-element-649ce4e elementor-button-align-stretch elementor-widget elementor-widget-form"
                data-id="649ce4e" 
                data-element_type="widget"
                data-settings="{&quot;step_next_label&quot;:&quot;Next&quot;,&quot;step_previous_label&quot;:&quot;Previous&quot;,&quot;button_width&quot;:&quot;100&quot;,&quot;step_type&quot;:&quot;number_text&quot;,&quot;step_icon_shape&quot;:&quot;circle&quot;}"
                data-widget_type="form.default"
            >
                <div class="elementor-widget-container">
                    <form wire:submit.prevent="resetPassword">

                        <div class="elementor-form-fields-wrapper elementor-labels-above">

                            
                            <div
                                class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-name elementor-col-50 elementor-md-100">
                                <label for="password" class="elementor-field-label">
                                    Password
                                </label>
                                <input id="password" size="1" type="text"
                                    class="elementor-field elementor-size-sm  elementor-field-textual"
                                    placeholder="********" wire:model.defer="password">

                                @error('password') <small class="text-red-500 text-xs">{{ $message }}</small> @enderror
                            </div>

                            <div
                                class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-name elementor-col-50 elementor-md-100">
                                <label for="password_confirmation" class="elementor-field-label">
                                    Confirm Password
                                </label>
                                <input 
                                    id="password_confirmation" 
                                    size="1" 
                                    type="text"
                                    class="elementor-field elementor-size-sm  elementor-field-textual"
                                    placeholder="********" 
                                    wire:model.defer="password_confirmation"
                                >
                            </div>

                            <div class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100 e-form__buttons">
                                <button class="elementor-button elementor-size-sm" type="submit"
                                    wire:loading.attr="disabled">
                                    <span class="elementor-button-content-wrapper">

                                        <span wire:loading.remove>
                                            Reset Password
                                        </span>

                                        <span wire:loading>
                                            Reseting...
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
