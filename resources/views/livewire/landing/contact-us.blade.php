<style>
    .faq-search-wrap {
        position: sticky;
        top: 0;
        z-index: 5;
        padding: 12px 0 16px;
        margin-bottom: 20px;
    }

    .faq-search-input {
        width: 100%;
        padding: 14px 16px;
        border-radius: 10px;
        border: 1px solid #e5e7eb;
        font-size: 15px;
        outline: none;
        transition: all .2s ease;
    }

    .faq-search-input:focus {
        border-color: #2563eb;
        box-shadow: 0 0 0 3px rgba(37,99,235,.12);
    }

    .faq-result-count {
        font-size: 13px;
        color: #6b7280;
        margin-top: 8px;
    }

    .e-n-accordion-item[hidden] {
        display: none !important;
    }

    mark {
        background: rgba(37,99,235,.15);
        color: inherit;
        padding: 0 2px;
        border-radius: 3px;
    }

    .form-locked {
        position: relative;
        opacity: .45;
        pointer-events: none;
    }

    .form-lock-overlay {
        position: absolute;
        inset: 0;
        /* background: rgba(255,255,255,.6); */
        backdrop-filter: blur(3px);
        display: flex;
        align-items: center;
        justify-content: center;
        text-align: center;
        padding: 24px;
        border-radius: 12px;
        pointer-events: all;
    }

    .form-lock-box {
        max-width: 420px;
        background: #fff;
        border-radius: 14px;
        padding: 22px;
        box-shadow: 0 20px 40px rgba(0,0,0,.08);
    }

    .form-lock-box h4 {
        margin: 0 0 8px;
        font-size: 18px;
        @apply text-gray-950;
        color: oklch(13% 0.028 261.692);
    }

    .form-lock-box p {
        font-size: 14px;
        color: #6b7280;
        margin: 0;
    }



    @media (max-width: 768px) {
        .faq-search-input {
            display:none;
            font-size: 15px;
            padding: 12px 14px;
        }
    }
</style>

<div
    class="wp-singular page-template page-template-elementor_header_footer page page-id-114 wp-embed-responsive wp-theme-hello-elementor hello-elementor-default elementor-default elementor-template-full-width elementor-kit-8 elementor-page elementor-page-114 e--ua-blink e--ua-chrome e--ua-webkit">
    <div data-elementor-type="wp-page" data-elementor-id="114" class="elementor elementor-114"
        data-elementor-post-type="page">

        <div class="elementor-element elementor-element-d94f2d6 e-flex e-con-boxed e-con e-parent e-lazyloaded"
            data-id="d94f2d6" data-element_type="container"
            data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
            <div class="e-con-inner">
                <div class="elementor-element elementor-element-2c14f63 e-con-full e-flex e-con e-child"
                    data-id="2c14f63" data-element_type="container">
                    <div class="elementor-element elementor-element-b7e0468 elementor-widget elementor-widget-heading animated fadeInUp"
                        data-id="b7e0468" data-element_type="widget"
                        data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:200}"
                        data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <h1 class="elementor-heading-title elementor-size-default">Contact us</h1>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-68551fe elementor-widget elementor-widget-heading animated fadeInUp"
                        data-id="68551fe" data-element_type="widget"
                        data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300}"
                        data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <h4 class="elementor-heading-title elementor-size-default">
                                Let’s Start a Meaningful Conversation About Your Financial Future
                            </h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>


        <div class="elementor-element elementor-element-d054b34 e-flex e-con-boxed e-con e-parent e-lazyloaded"
            data-id="d054b34" data-element_type="container">
            <div class="e-con-inner">
                <div data-elementor-type="wp-page" data-elementor-id="295" class="elementor elementor-295"
                    data-elementor-post-type="page">
                    <div class="elementor-element elementor-element-9cdca02 e-con-full e-flex e-con e-parent e-lazyloaded"
                        data-id="9cdca02" data-element_type="container"
                        data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">

                        <div class="elementor-element elementor-element-65daf70 e-con-full e-flex e-con e-child animated fadeInRight"
                            data-id="65daf70" data-element_type="container"
                            data-settings="{&quot;animation&quot;:&quot;fadeInRight&quot;}">
                            <div class="elementor-element elementor-element-283fe67 elementor-widget elementor-widget-heading"
                                data-id="283fe67" data-element_type="widget" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                    <h2 class="elementor-heading-title elementor-size-default">
                                        Check if your question has been answerd.
                                    </h2>
                                </div>
                            </div>
                            <div class="elementor-element elementor-element-9803166 elementor-widget elementor-widget-heading"
                                data-id="9803166" data-element_type="widget" data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                    <div class="elementor-heading-title elementor-size-default">
                                        Before contactiong us, please check through to confirm that your question has
                                        not been given answers to.
                                    </div>
                                </div>
                            </div>
                            <div class="elementor-element elementor-element-3a4b5cf elementor-widget elementor-widget-n-accordion"
                                data-id="3a4b5cf" data-element_type="widget"
                                data-settings="{&quot;default_state&quot;:&quot;expanded&quot;,&quot;max_items_expended&quot;:&quot;one&quot;,&quot;n_accordion_animation_duration&quot;:{&quot;unit&quot;:&quot;ms&quot;,&quot;size&quot;:400,&quot;sizes&quot;:[]}}"
                                data-widget_type="nested-accordion.default">
                                <div class="elementor-widget-container">
                                    <div class="e-n-accordion"
                                        aria-label="Accordion. Open links with Enter or Space, close with Escape, and navigate with Arrow Keys">
                                        <details id="e-n-accordion-item-6110" class="e-n-accordion-item" style="">
                                            <summary class="e-n-accordion-item-title" data-accordion-index="1"
                                                tabindex="0" aria-expanded="false"
                                                aria-controls="e-n-accordion-item-6110">
                                                <span class="e-n-accordion-item-title-header">
                                                    <div class="e-n-accordion-item-title-text">
                                                        How does the platform personalize my investment strategy?
                                                    </div>
                                                </span>
                                                <span class="e-n-accordion-item-title-icon">
                                                    <span class="e-opened"><i aria-hidden="true"
                                                            class="mdi mdi-checkbox-marked-circle"></i></span>
                                                    <span class="e-closed"><i aria-hidden="true"
                                                            class="mdi mdi-check-circle-outline"></i></span>
                                                </span>

                                            </summary>
                                            <div role="region" aria-labelledby="e-n-accordion-item-6110"
                                                class="elementor-element elementor-element-90a8056 e-con-full e-flex e-con e-child"
                                                data-id="90a8056" data-element_type="container">
                                                <div class="elementor-element elementor-element-e86ce1f elementor-widget elementor-widget-text-editor"
                                                    data-id="e86ce1f" data-element_type="widget"
                                                    data-widget_type="text-editor.default">
                                                    <div class="elementor-widget-container">
                                                        <p>
                                                            Your strategy is tailored based on your goals, risk
                                                            tolerance,
                                                            and investment
                                                            horizon, ensuring a balanced approach between growth,
                                                            stability,
                                                            and long-term
                                                            returns.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="elementor-element elementor-element-10e296a elementor-absolute elementor-widget elementor-widget-heading"
                                                    data-id="10e296a" data-element_type="widget"
                                                    data-settings="{&quot;_position&quot;:&quot;absolute&quot;}"
                                                    data-widget_type="heading.default">
                                                    <div class="elementor-widget-container">
                                                        <div class="elementor-heading-title elementor-size-default">01
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </details>
                                        <details id="e-n-accordion-item-6111" class="e-n-accordion-item" style="">
                                            <summary class="e-n-accordion-item-title" data-accordion-index="2"
                                                tabindex="-1" aria-expanded="false"
                                                aria-controls="e-n-accordion-item-6111">
                                                <span class="e-n-accordion-item-title-header">
                                                    <div class="e-n-accordion-item-title-text">
                                                        What technology powers your trading and investment systems?
                                                    </div>
                                                </span>
                                                <span class="e-n-accordion-item-title-icon">
                                                    <span class="e-opened"><i aria-hidden="true"
                                                            class="mdi mdi-checkbox-marked-circle"></i></span>
                                                    <span class="e-closed"><i aria-hidden="true"
                                                            class="mdi mdi-check-circle-outline"></i></span>
                                                </span>

                                            </summary>
                                            <div role="region" aria-labelledby="e-n-accordion-item-6111"
                                                class="elementor-element elementor-element-a0d9a34 e-con-full e-flex e-con e-child"
                                                data-id="a0d9a34" data-element_type="container">
                                                <div class="elementor-element elementor-element-95d8bd2 elementor-widget elementor-widget-text-editor"
                                                    data-id="95d8bd2" data-element_type="widget"
                                                    data-widget_type="text-editor.default">
                                                    <div class="elementor-widget-container">
                                                        <p>
                                                            We leverage advanced trading infrastructure, real-time
                                                            market
                                                            analysis, and automated systems to execute strategies
                                                            efficiently and transparently.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="elementor-element elementor-element-d8e157d elementor-absolute elementor-widget elementor-widget-heading"
                                                    data-id="d8e157d" data-element_type="widget"
                                                    data-settings="{&quot;_position&quot;:&quot;absolute&quot;}"
                                                    data-widget_type="heading.default">
                                                    <div class="elementor-widget-container">
                                                        <div class="elementor-heading-title elementor-size-default">02
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </details>
                                        <details id="e-n-accordion-item-6112" class="e-n-accordion-item" style="">
                                            <summary class="e-n-accordion-item-title" data-accordion-index="3"
                                                tabindex="-1" aria-expanded="false"
                                                aria-controls="e-n-accordion-item-6112">
                                                <span class="e-n-accordion-item-title-header">
                                                    <div class="e-n-accordion-item-title-text">
                                                        What investment plans and wealth solutions are available?
                                                    </div>
                                                </span>
                                                <span class="e-n-accordion-item-title-icon">
                                                    <span class="e-opened"><i aria-hidden="true"
                                                            class="mdi mdi-checkbox-marked-circle"></i></span>
                                                    <span class="e-closed"><i aria-hidden="true"
                                                            class="mdi mdi-check-circle-outline"></i></span>
                                                </span>

                                            </summary>
                                            <div role="region" aria-labelledby="e-n-accordion-item-6112"
                                                class="elementor-element elementor-element-99287f0 e-con-full e-flex e-con e-child"
                                                data-id="99287f0" data-element_type="container">
                                                <div class="elementor-element elementor-element-d05ba4e elementor-widget elementor-widget-text-editor"
                                                    data-id="d05ba4e" data-element_type="widget"
                                                    data-widget_type="text-editor.default">
                                                    <div class="elementor-widget-container">
                                                        <p>
                                                            We offer diversified plans covering short-term growth,
                                                            long-term
                                                            wealth building, and risk-managed portfolios designed to
                                                            match
                                                            different financial objectives.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="elementor-element elementor-element-5fbfd82 elementor-absolute elementor-widget elementor-widget-heading"
                                                    data-id="5fbfd82" data-element_type="widget"
                                                    data-settings="{&quot;_position&quot;:&quot;absolute&quot;}"
                                                    data-widget_type="heading.default">
                                                    <div class="elementor-widget-container">
                                                        <div class="elementor-heading-title elementor-size-default">03
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </details>
                                        <details id="e-n-accordion-item-6113" class="e-n-accordion-item" style="">
                                            <summary class="e-n-accordion-item-title" data-accordion-index="4"
                                                tabindex="-1" aria-expanded="false"
                                                aria-controls="e-n-accordion-item-6113">
                                                <span class="e-n-accordion-item-title-header">
                                                    <div class="e-n-accordion-item-title-text">
                                                        How can I monitor performance and withdraw my earnings?
                                                    </div>
                                                </span>
                                                <span class="e-n-accordion-item-title-icon">
                                                    <span class="e-opened"><i aria-hidden="true"
                                                            class="mdi mdi-checkbox-marked-circle"></i></span>
                                                    <span class="e-closed"><i aria-hidden="true"
                                                            class="mdi mdi-check-circle-outline"></i></span>
                                                </span>

                                            </summary>
                                            <div role="region" aria-labelledby="e-n-accordion-item-6113"
                                                class="elementor-element elementor-element-55ec418 e-con-full e-flex e-con e-child"
                                                data-id="55ec418" data-element_type="container">
                                                <div class="elementor-element elementor-element-2e37f9d elementor-widget elementor-widget-text-editor"
                                                    data-id="2e37f9d" data-element_type="widget"
                                                    data-widget_type="text-editor.default">
                                                    <div class="elementor-widget-container">
                                                        <p>
                                                            Your dashboard provides real-time performance tracking,
                                                            earnings
                                                            history, and clear withdrawal options based on your selected
                                                            plan’s terms.
                                                        </p>
                                                    </div>
                                                </div>
                                                <div class="elementor-element elementor-element-7dcd4e0 elementor-absolute elementor-widget elementor-widget-heading"
                                                    data-id="7dcd4e0" data-element_type="widget"
                                                    data-settings="{&quot;_position&quot;:&quot;absolute&quot;}"
                                                    data-widget_type="heading.default">
                                                    <div class="elementor-widget-container">
                                                        <div class="elementor-heading-title elementor-size-default">04
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        </details>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>


                <div class="elementor-element elementor-element-a1c12d4 e-con-full e-flex e-con e-child animated fadeInLeft"
                    data-id="a1c12d4" data-element_type="container"
                    data-settings="{&quot;animation&quot;:&quot;fadeInLeft&quot;}">
                    <div class="elementor-element elementor-element-c3e1e76 elementor-widget elementor-widget-heading"
                        data-id="c3e1e76" data-element_type="widget" data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <h2 class="elementor-heading-title elementor-size-default">
                                Have Questions? Our Investment Experts Are Ready to Help
                            </h2>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-cc6086c elementor-widget elementor-widget-heading"
                        data-id="cc6086c" data-element_type="widget" data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <div class="elementor-heading-title elementor-size-default">
                                Whether you’re exploring investment opportunities, need clarity on our services, or want
                                personalized guidance, our team is here to support you every step of the way.
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
                            <form class="elementor-form" method="post" name="New Form" aria-label="New Form">
                                <input type="hidden" name="post_id" value="114">
                                <input type="hidden" name="form_id" value="649ce4e">
                                <input type="hidden" name="referer_title" value="Contact us">

                                <input type="hidden" name="queried_id" value="114">

                                <div class="elementor-form-fields-wrapper elementor-labels-above">
                                    <div
                                        class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-name elementor-col-50 elementor-md-100">
                                        <label for="form-field-name" class="elementor-field-label">
                                            Name </label>
                                        <input size="1" type="text" name="form_fields[name]" id="form-field-name"
                                            class="elementor-field elementor-size-sm  elementor-field-textual"
                                            placeholder="Name">
                                    </div>
                                    <div
                                        class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-field_98da870 elementor-col-50 elementor-md-100">
                                        <label for="form-field-field_98da870" class="elementor-field-label">
                                            Company </label>
                                        <input size="1" type="text" name="form_fields[field_98da870]"
                                            id="form-field-field_98da870"
                                            class="elementor-field elementor-size-sm  elementor-field-textual"
                                            placeholder="Company">
                                    </div>
                                    <div
                                        class="elementor-field-type-tel elementor-field-group elementor-column elementor-field-group-email elementor-col-50 elementor-field-required">
                                        <label for="form-field-email" class="elementor-field-label">
                                            Phone </label>
                                        <input size="1" type="tel" name="form_fields[email]" id="form-field-email"
                                            class="elementor-field elementor-size-sm  elementor-field-textual"
                                            placeholder="Phone" required="required" pattern="[0-9()#&amp;+*-=.]+"
                                            title="Only numbers and phone characters (#, -, *, etc) are accepted.">

                                    </div>
                                    <div
                                        class="elementor-field-type-email elementor-field-group elementor-column elementor-field-group-field_a4a465e elementor-col-50 elementor-field-required">
                                        <label for="form-field-field_a4a465e" class="elementor-field-label">
                                            Email </label>
                                        <input size="1" type="email" name="form_fields[field_a4a465e]"
                                            id="form-field-field_a4a465e"
                                            class="elementor-field elementor-size-sm  elementor-field-textual"
                                            placeholder="Email" required="required">
                                    </div>
                                    <div
                                        class="elementor-field-type-text elementor-field-group elementor-column elementor-field-group-field_2c7e43b elementor-col-100 elementor-field-required">
                                        <label for="form-field-field_2c7e43b" class="elementor-field-label">
                                            Subject </label>
                                        <input size="1" type="text" name="form_fields[field_2c7e43b]"
                                            id="form-field-field_2c7e43b"
                                            class="elementor-field elementor-size-sm  elementor-field-textual"
                                            placeholder="Subject" required="required">
                                    </div>
                                    <div
                                        class="elementor-field-type-textarea elementor-field-group elementor-column elementor-field-group-message elementor-col-100">
                                        <label for="form-field-message" class="elementor-field-label">
                                            Message </label>
                                        <textarea class="elementor-field-textual elementor-field  elementor-size-sm"
                                            name="form_fields[message]" id="form-field-message" rows="4"
                                            placeholder="Message"></textarea>
                                    </div>
                                    <div
                                        class="elementor-field-group elementor-column elementor-field-type-submit elementor-col-100 e-form__buttons">
                                        <button class="elementor-button elementor-size-sm" type="submit">
                                            <span class="elementor-button-content-wrapper">
                                                <span class="elementor-button-icon">
                                                    <i aria-hidden="true" class="icons icon-envelope"></i> </span>
                                                <span class="elementor-button-text">Send Message</span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <div class="elementor-element elementor-element-ede271e e-con-full e-flex e-con e-child animated fadeInRight"
                    data-id="ede271e" data-element_type="container"
                    data-settings="{&quot;animation&quot;:&quot;fadeInRight&quot;}">
                    <div class="elementor-element elementor-element-3cca742 elementor-widget__width-initial elementor-hidden-tablet elementor-widget elementor-widget-image animated fadeInLeft"
                        data-id="3cca742" data-element_type="widget"
                        data-settings="{&quot;_animation&quot;:&quot;fadeInLeft&quot;,&quot;_animation_delay&quot;:200}"
                        data-widget_type="image.default">
                        <div class="elementor-widget-container">
                            <img fetchpriority="high" decoding="async" width="1280" height="950"
                                src="../wp-content/uploads/sites/9/2025/04/office-building-from-low-angle-view.jpg"
                                class="attachment-full size-full wp-image-124" alt="Office building from low angle view"
                                srcset="https://demokit.creativemox.com/capwise/wp-content/uploads/sites/9/2025/04/office-building-from-low-angle-view.jpg 1280w, https://demokit.creativemox.com/capwise/wp-content/uploads/sites/9/2025/04/office-building-from-low-angle-view-300x223.jpg 300w, https://demokit.creativemox.com/capwise/wp-content/uploads/sites/9/2025/04/office-building-from-low-angle-view-1024x760.jpg 1024w, https://demokit.creativemox.com/capwise/wp-content/uploads/sites/9/2025/04/office-building-from-low-angle-view-768x570.jpg 768w, https://demokit.creativemox.com/capwise/wp-content/uploads/sites/9/2025/04/office-building-from-low-angle-view-1536x1140.jpg 1536w, https://demokit.creativemox.com/capwise/wp-content/uploads/sites/9/2025/04/office-building-from-low-angle-view-800x594.jpg 800w"
                                sizes="(max-width: 1280px) 100vw, 1280px">
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-e30a5ca e-con-full e-flex e-con e-child animated fadeInRight"
                        data-id="e30a5ca" data-element_type="container"
                        data-settings="{&quot;background_background&quot;:&quot;classic&quot;,&quot;animation&quot;:&quot;fadeInRight&quot;,&quot;animation_delay&quot;:300}">
                        <div class="elementor-element elementor-element-c276f6c elementor-widget elementor-widget-heading animated fadeInUp"
                            data-id="c276f6c" data-element_type="widget"
                            data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300}"
                            data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h6 class="elementor-heading-title elementor-size-default">Get in touch</h6>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-4aa7a04 elementor-widget elementor-widget-heading"
                            data-id="4aa7a04" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h3 class="elementor-heading-title elementor-size-default">
                                    LLet’s Discuss Your Investment Goals and Strategy
                                </h3>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-d07ac2e elementor-widget elementor-widget-heading"
                            data-id="d07ac2e" data-element_type="widget" data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-heading-title elementor-size-default">
                                    Connect with our financial professionals to design a strategy tailored to your
                                    objectives, risk profile, and long-term vision.
                                </div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-6bfc2f2 elementor-widget-divider--view-line elementor-widget elementor-widget-divider"
                            data-id="6bfc2f2" data-element_type="widget" data-widget_type="divider.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-divider">
                                    <span class="elementor-divider-separator">
                                    </span>
                                </div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-1a5193f elementor-view-stacked elementor-shape-rounded elementor-position-left elementor-mobile-position-left elementor-widget elementor-widget-icon-box"
                            data-id="1a5193f" data-element_type="widget" data-widget_type="icon-box.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-icon-box-wrapper">

                                    <div class="elementor-icon-box-icon">
                                        <span class="elementor-icon">
                                            <i aria-hidden="true" class="icons icon-location-pin"></i> </span>
                                    </div>

                                    <div class="elementor-icon-box-content">

                                        <div class="elementor-icon-box-title">
                                            <span>
                                                Head Office </span>
                                        </div>

                                        <p class="elementor-icon-box-description">
                                            Our headquarters serves as the core of our research, strategy, and client
                                            advisory operations.
                                            ({address})
                                        </p>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-707cd7c elementor-view-stacked elementor-shape-rounded elementor-position-left elementor-mobile-position-left elementor-widget elementor-widget-icon-box"
                            data-id="707cd7c" data-element_type="widget" data-widget_type="icon-box.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-icon-box-wrapper">

                                    <div class="elementor-icon-box-icon">
                                        <span class="elementor-icon">
                                            <i aria-hidden="true" class="icons icon-envelope"></i> </span>
                                    </div>

                                    <div class="elementor-icon-box-content">

                                        <div class="elementor-icon-box-title">
                                            <span>
                                                Email Support </span>
                                        </div>

                                        <p class="elementor-icon-box-description">
                                            support@invoraai.com <br>
                                            hello@invoraai.com
                                        </p>

                                    </div>

                                </div>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-57b19be elementor-view-stacked elementor-shape-rounded elementor-position-left elementor-mobile-position-left elementor-widget elementor-widget-icon-box"
                            data-id="57b19be" data-element_type="widget" data-widget_type="icon-box.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-icon-box-wrapper">

                                    <div class="elementor-icon-box-icon">
                                        <span class="elementor-icon">
                                            <i aria-hidden="true" class="icons icon-paper-plane"></i> </span>
                                    </div>

                                    <div class="elementor-icon-box-content">

                                        <div class="elementor-icon-box-title">
                                            <span>
                                                Let's Talk </span>
                                        </div>

                                        <p class="elementor-icon-box-description">
                                            WhatsApp : +6221.2002.2012 <br>
                                        </p>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

    </div>
</div>


<!-- <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script> -->

<script>
    document.addEventListener('DOMContentLoaded', () => {

    
    const accordion = document.querySelector('.e-n-accordion');
    if (!accordion) return;

    const faqItems = [...accordion.querySelectorAll('.e-n-accordion-item')];

    
    const searchWrap = document.createElement('div');
    searchWrap.className = 'faq-search-wrap';
    searchWrap.innerHTML = `
        <input type="search" class="faq-search-input" placeholder="Search your question…">
        <div class="faq-result-count"></div>
    `;
    accordion.parentNode.insertBefore(searchWrap, accordion);

    const input = searchWrap.querySelector('input');
    const count = searchWrap.querySelector('.faq-result-count');

    const normalize = str => str.toLowerCase();

    function highlight(el, term) {
        el.innerHTML = el.textContent.replace(
            new RegExp(`(${term})`, 'gi'),
            '<mark>$1</mark>'
        );
    }

    function reset(el) {
        el.innerHTML = el.textContent;
    }

    input.addEventListener('input', e => {
        const q = normalize(e.target.value.trim());
        let visible = 0;

        faqItems.forEach(item => {
            const title = item.querySelector('.e-n-accordion-item-title-text');
            const body = item.querySelector('p');

            reset(title);
            reset(body);

            if (!q) {
                item.hidden = false;
                return;
            }

            const text = normalize(title.textContent + ' ' + body.textContent);

            if (text.includes(q)) {
                item.hidden = false;
                highlight(title, q);
                highlight(body, q);
                visible++;
            } else {
                item.hidden = true;
            }
        });

        count.textContent = q
            ? `${visible} result${visible !== 1 ? 's' : ''} found`
            : '';
    });

    
    const formWidget = document.querySelector('.elementor-widget-form');
    if (!formWidget) return;

    formWidget.classList.add('form-locked');

    const overlay = document.createElement('div');
    overlay.className = 'form-lock-overlay';
    overlay.innerHTML = `
        <div class="form-lock-box">
            <h4 class="text-gray-900">Have you checked our FAQs?</h4>
            <p>
                Many questions are already answered above.
                Please search or open a question before contacting our team.
            </p>
        </div>
    `;
    formWidget.appendChild(overlay);

    let unlocked = false;

    function unlockForm() {
        if (unlocked) return;
        unlocked = true;
        formWidget.classList.remove('form-locked');
        overlay.remove();
    }

    input.addEventListener('input', e => {
        if (e.target.value.trim().length > 2) unlockForm();
    });

    accordion.addEventListener('toggle', e => {
        if (e.target.tagName === 'DETAILS' && e.target.open) {
            unlockForm();
        }
    }, true);

});
</script>
