<div
    class="elementor-element elementor-element-d054b34 e-flex e-con-boxed e-con e-parent e-lazyloaded"
    data-id="d054b34" data-element_type="container">
    <div class="e-con-inner">
        <div class="elementor-element elementor-element-a1c12d4 e-con-full e-flex e-con e-child animated fadeInLeft"
            data-id="a1c12d4" data-element_type="container"
            data-settings="{&quot;animation&quot;:&quot;fadeInLeft&quot;}">

            <div class="elementor-element elementor-element-c3e1e76 elementor-widget elementor-widget-heading"
                data-id="c3e1e76" data-element_type="widget" data-widget_type="heading.default">
                <div class="elementor-widget-container">
                    <h2 class="elementor-heading-title elementor-size-default">
                        Verification Failed
                    </h2>
                </div>
            </div>

            <div class="elementor-element elementor-element-cc6086c elementor-widget elementor-widget-heading"
                data-id="cc6086c" data-element_type="widget" data-widget_type="heading.default">
                <div class="elementor-widget-container">
                    <div class="elementor-heading-title elementor-size-default">
                        The verification link is invalid, expired, or already used. Please request a new verification email or contact support.
                    </div>
                </div>
            </div>

            <div class="elementor-element elementor-element-1c0c898 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                data-id="1c0c898" data-element_type="widget" data-widget_type="divider.default">
                <div class="elementor-widget-container">
                    <div class="elementor-divider">
                        <span class="elementor-divider-separator"></span>
                    </div>
                </div>
            </div>

            <div class="elementor-element elementor-element-649ce4e elementor-button-align-stretch elementor-widget elementor-widget-form"
                data-id="649ce4e" data-element_type="widget"
                data-settings="{&quot;button_width&quot;:&quot;100&quot;}" data-widget_type="form.default">
                <div class="elementor-widget-container">
                    <div class="elementor-form-fields-wrapper elementor-labels-above">
                        <div class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100 e-form__buttons">
                            <a href="{{ route('resend.verification') }}" class="elementor-button elementor-size-sm">
                                <span class="elementor-button-content-wrapper">
                                    <span class="elementor-button-icon">
                                        <i aria-hidden="true" class="icons icon-refresh"></i>
                                    </span>
                                    <span class="elementor-button-text">
                                        Resend Verification Email
                                    </span>
                                </span>
                            </a>
                        </div>
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
