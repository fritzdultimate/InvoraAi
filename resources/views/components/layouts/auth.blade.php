<!doctype html>
<html lang="en-US">
<meta http-equiv="content-type" content="text/html;charset=UTF-8" />
@include('components.layouts.guest.head')

<body
    class="home wp-singular page-template page-template-elementor_header_footer page page-id-681 wp-embed-responsive wp-theme-hello-elementor hello-elementor-default elementor-default elementor-template-full-width elementor-kit-8 elementor-page elementor-page-681">
    @include('components.layouts.guest.header')

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
                                <h1 class="elementor-heading-title elementor-size-default">{{ $title ?? 'Account Access' }}</h1>
                            </div>
                        </div>
                        <div class="elementor-element elementor-element-68551fe elementor-widget elementor-widget-heading animated fadeInUp"
                            data-id="68551fe" data-element_type="widget"
                            data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300}"
                            data-widget_type="heading.default">
                            <div class="elementor-widget-container">
                                <h4 class="elementor-heading-title elementor-size-default">
                                    {{ $subtitle ?? 'Secure access to your investment dashboard' }}
                                </h4>
                            </div>
                        </div>
                    </div>
                </div>
            </div>


            {{ $slot }}
        </div>
    </div>


    @include('components.layouts.guest.footer')
    @include('components.layouts.guest.footer-scripts')
</body>

</html>