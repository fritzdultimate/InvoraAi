<!doctype html>
<html class="no-js" lang="en">
    <head>
        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>{{ env('APP_NAME') }}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">

        <!-- CSS here -->
        <link rel="stylesheet" href="{{ asset('new_assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/animate.min.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/fontawesome-all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/mCustomScrollbar.min.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/odometer.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/slick.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/default.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/responsive.css') }}">

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

        <style type="text/css" id="jarallax-clip-0">#jarallax-container-0 {
            clip: rect(0 362.3999938964844px 1293.7000732421875px 0);
            clip: rect(0, 362.3999938964844px, 1293.7000732421875px, 0);
            -webkit-clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
        }</style>

        <style>
            /* image wrap */

            .invora-image-wrap {
                padding: 12px;
                border-radius: 16px;

                background: rgba(255,255,255,0.03);
                backdrop-filter: blur(8px);

                border: 1px solid rgba(255,255,255,0.08);

                box-shadow: 0 20px 50px rgba(0,0,0,0.4);
            }

            .invora-image-wrap img {
                width: 100%;
                border-radius: 10px;
                display: block;
            }   
        </style>
    </head>

    <body class="home-01">
        @include('components.layouts.landing.preloader')
        @include('components.layouts.landing.header')

        {{ $slot }}

        @include('components.layouts.landing.footer')
        @include('components.layouts.landing.footer-scripts')
        @include('components.layouts.live-chat')
    </body>
</html>