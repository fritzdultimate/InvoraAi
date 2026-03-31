<!doctype html>
<html class="no-js" lang="en">
    <head>
        @include('components.layouts.partials.landing-head-inner')
    </head>

    <body class="home-01">
        @if($showPreloader ?? false)
            @include('components.layouts.landing.preloader')
        @endif
        @include('components.layouts.landing.header')

        {{ $slot }}

        @include('components.layouts.landing.footer')
        @include('components.layouts.landing.footer-scripts')
        @include('components.layouts.live-chat')
    </body>
</html>
