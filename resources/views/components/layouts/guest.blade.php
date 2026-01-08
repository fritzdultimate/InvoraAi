<!doctype html>
<html lang="en-US">
    <meta http-equiv="content-type" content="text/html;charset=UTF-8" />
    @include('components.layouts.guest.head')

    <body class="home wp-singular page-template page-template-elementor_header_footer page page-id-681 wp-embed-responsive wp-theme-hello-elementor hello-elementor-default elementor-default elementor-template-full-width elementor-kit-8 elementor-page elementor-page-681">
        @include('components.layouts.guest.header')

        {{ $slot }}

        @include('components.layouts.guest.footer')
        @include('components.layouts.guest.footer-scripts')
        @include('components.layouts.live-chat')
    </body>
</html>