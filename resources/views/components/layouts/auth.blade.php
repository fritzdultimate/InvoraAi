<!doctype html>
<html class="no-js" lang="en">
    <head>
        @include('components.layouts.partials.landing-head-inner')
        @vite(['resources/css/app.css'])
        <link rel="stylesheet" href="{{ asset('wp-content/plugins/elementor/assets/css/frontend.min37de.css') }}">
        <link rel="stylesheet" href="{{ asset('wp-content/plugins/elementor/assets/css/widget-heading.min37de.css') }}">
        <link rel="stylesheet" href="{{ asset('wp-content/plugins/elementor-pro/assets/css/widget-form.min7ddb.css') }}">
        <link rel="stylesheet" href="{{ asset('wp-content/plugins/elementor/assets/css/widget-divider.min37de.css') }}">
        @livewireStyles
        <style>
            body.auth-layout-page {
                background-color: #030b15;
                color: #a4b4c3;
                position: relative;
                /* Align with landing / logo-adjacent green (see landing-head-inner vision accents) */
                --invora-brand-green: #00b08b;
                --invora-brand-green-hover: #00c99a;
                --invora-brand-green-soft: rgba(0, 176, 139, 0.28);
                /* Flux + app.css focus rings / primary buttons on forgot-password, etc. */
                --color-accent: #00b08b;
                --color-accent-content: #ffffff;
                --color-accent-foreground: #ffffff;
            }
            /* Subtle robot image — right side only, blends into #030b15 */
            body.auth-layout-page::before {
                content: '';
                position: fixed;
                top: 0;
                right: 0;
                width: min(58vw, 720px);
                height: 100vh;
                z-index: 0;
                pointer-events: none;
                background: url('{{ asset('new_assets/img/images/robot2.jpg') }}') no-repeat center center / cover;
                opacity: 0.26;
                mask-image: linear-gradient(
                    90deg,
                    transparent 0%,
                    rgba(0, 0, 0, 0.25) 22%,
                    rgba(0, 0, 0, 0.65) 48%,
                    #000 100%
                );
                -webkit-mask-image: linear-gradient(
                    90deg,
                    transparent 0%,
                    rgba(0, 0, 0, 0.25) 22%,
                    rgba(0, 0, 0, 0.65) 48%,
                    #000 100%
                );
            }
            /* Header above main so desktop "Others" sub-menu is not covered by main (same z-index + DOM order hid dropdown) */
            body.auth-layout-page > header {
                position: relative;
                z-index: 200;
            }
            body.auth-layout-page > main,
            body.auth-layout-page > footer {
                position: relative;
                z-index: 1;
            }
            body.auth-layout-page .navbar-wrap ul li .sub-menu {
                z-index: 300;
            }
            /* Header: match accent to brand green (theme CSS still uses cyan #00C4F4) */
            body.auth-layout-page .navbar-wrap .navigation > li:not(.header-btn) > a:hover,
            body.auth-layout-page .navbar-wrap .navigation > li:not(.header-btn) > a:focus {
                color: var(--invora-brand-green) !important;
            }
            body.auth-layout-page .header-btn .btn {
                background: var(--invora-brand-green) !important;
                border: 2px solid var(--invora-brand-green) !important;
                color: #ffffff !important;
            }
            body.auth-layout-page .header-btn .btn::after {
                display: none !important;
            }
            body.auth-layout-page .header-btn .btn:hover {
                background: var(--invora-brand-green-hover) !important;
                border-color: var(--invora-brand-green-hover) !important;
                color: #ffffff !important;
            }
            body.auth-layout-page .header-lang .lang-list li a:hover {
                color: var(--invora-brand-green) !important;
            }
            @media (max-width: 767.98px) {
                body.auth-layout-page::before {
                    width: min(78vw, 100%);
                    opacity: 0.16;
                    mask-image: linear-gradient(
                        90deg,
                        transparent 0%,
                        rgba(0, 0, 0, 0.35) 35%,
                        #000 100%
                    );
                    -webkit-mask-image: linear-gradient(
                        90deg,
                        transparent 0%,
                        rgba(0, 0, 0, 0.35) 35%,
                        #000 100%
                    );
                }
            }
            .auth-layout-main {
                padding-top: clamp(118px, 10vw, 152px);
                padding-bottom: clamp(56px, 8vw, 96px);
            }
            .auth-shell {
                width: min(920px, 100% - 2rem);
                margin-inline: auto;
            }
            .auth-shell .e-con-inner {
                max-width: 100%;
                padding: 0;
            }
            .auth-shell .e-con.e-child {
                border: 1px solid rgba(157, 188, 212, 0.2);
                border-radius: 20px;
                background: linear-gradient(180deg, rgba(13, 27, 44, 0.93) 0%, rgba(8, 20, 34, 0.94) 100%);
                box-shadow: 0 30px 60px rgba(0, 0, 0, 0.36);
                backdrop-filter: blur(8px);
                padding: clamp(1.3rem, 2vw, 2rem);
            }
            .auth-layout-main .elementor-heading-title {
                color: #e6edf3;
                line-height: 1.35;
            }
            .auth-layout-main .elementor-field-label {
                color: #c5d9e8;
                margin-bottom: 0.45rem;
            }
            .auth-layout-main .elementor-widget:not(:last-child) {
                margin-bottom: 1.1rem;
            }
            /* Space between input rows/columns (Elementor markup + flex gap) */
            .auth-layout-main .elementor-form-fields-wrapper {
                display: flex;
                flex-wrap: wrap;
                align-items: flex-start;
                align-content: flex-start;
                gap: 1.5rem 1.35rem;
            }
            .auth-layout-main .elementor-field-group {
                margin-bottom: 0 !important;
            }
            .auth-layout-main .elementor-field {
                min-height: 48px;
                border-radius: 10px;
                border: 1px solid rgba(150, 180, 204, 0.28);
                background: rgba(3, 11, 21, 0.84);
                color: #dbe8f4;
                padding-inline: 0.85rem;
            }
            .auth-layout-main .elementor-field::placeholder {
                color: #89a3b8;
            }
            .auth-layout-main .elementor-divider {
                margin-block: 0.6rem 1rem;
            }
            .auth-layout-main .elementor-divider-separator {
                border-color: rgba(168, 196, 218, 0.26);
            }
            .auth-layout-main .elementor-button {
                min-height: 47px;
                border-radius: 10px;
                background-color: var(--invora-brand-green) !important;
                border: 1px solid var(--invora-brand-green) !important;
                color: #ffffff !important;
            }
            .auth-layout-main .elementor-button:hover {
                background-color: var(--invora-brand-green-hover) !important;
                border-color: var(--invora-brand-green-hover) !important;
                color: #ffffff !important;
            }
            .auth-layout-main .elementor-button,
            .auth-layout-main .elementor-button:focus,
            .auth-layout-main .elementor-button:hover {
                text-decoration: none;
            }
            .auth-layout-main .elementor-field:focus {
                border-color: rgba(0, 176, 139, 0.55) !important;
                box-shadow: 0 0 0 3px var(--invora-brand-green-soft);
            }
            .auth-layout-main .text-gray-600,
            .auth-layout-main .text-zinc-400 {
                color: #9bb2c6 !important;
            }
            .auth-layout-main .text-primary,
            .auth-layout-main a.text-primary {
                color: var(--invora-brand-green) !important;
            }
            .auth-layout-main .text-primary:hover,
            .auth-layout-main a.text-primary:hover {
                color: var(--invora-brand-green-hover) !important;
            }
            .auth-layout-main a[href].text-accent,
            .auth-layout-main .text-accent {
                color: var(--invora-brand-green) !important;
            }
            /* Flux-based screens (forgot password, confirm password, etc.) */
            .auth-layout-main .auth-flux-panel {
                border: 1px solid rgba(157, 188, 212, 0.1);
                border-radius: 18px;
                background: rgba(6, 18, 32, 0.5);
                box-shadow: 0 16px 44px rgba(0, 0, 0, 0.2);
                padding: clamp(1.35rem, 2.2vw, 1.85rem);
            }
            .auth-layout-main .auth-flux-panel [data-flux-heading] {
                color: #e6edf3 !important;
                font-weight: 600;
                letter-spacing: -0.02em;
            }
            .auth-layout-main .auth-flux-panel [data-flux-subheading] {
                color: #8fa3b5 !important;
                font-size: 0.9375rem;
                line-height: 1.55;
            }
            .auth-layout-main .auth-flux-panel [data-flux-label] {
                color: #b5c7d6 !important;
            }
            .auth-layout-main .auth-flux-panel [data-flux-control] {
                min-height: 46px;
                border-radius: 10px !important;
                background: rgba(3, 11, 21, 0.55) !important;
                border-color: rgba(150, 180, 204, 0.22) !important;
                color: #dbe8f4 !important;
                box-shadow: none !important;
            }
            .auth-layout-main .auth-flux-panel [data-flux-control]::placeholder {
                color: #7a92a0 !important;
            }
            .auth-layout-main .auth-flux-panel [data-flux-control]:focus {
                border-color: rgba(0, 176, 139, 0.45) !important;
                box-shadow: 0 0 0 3px rgba(0, 176, 139, 0.12) !important;
            }
            /* Softer CTA than full solid green */
            .auth-layout-main .auth-flux-panel [data-flux-button] {
                background: rgba(0, 176, 139, 0.2) !important;
                border: 1px solid rgba(0, 176, 139, 0.38) !important;
                color: #dff5ee !important;
                box-shadow: none !important;
            }
            .auth-layout-main .auth-flux-panel [data-flux-button]:hover {
                background: rgba(0, 176, 139, 0.3) !important;
                border-color: rgba(0, 176, 139, 0.5) !important;
                color: #f4fffb !important;
            }
            .auth-layout-main .auth-flux-panel .auth-flux-footer-link,
            .auth-layout-main .auth-flux-panel [data-flux-link] {
                color: rgba(165, 220, 205, 0.88) !important;
                text-decoration-color: rgba(0, 176, 139, 0.28) !important;
            }
            .auth-layout-main .auth-flux-panel .auth-flux-footer-link:hover,
            .auth-layout-main .auth-flux-panel [data-flux-link]:hover {
                color: #dff5ee !important;
            }
        </style>
    </head>

    <body class="home-01 auth-layout-page">
        @include('components.layouts.landing.header')

        <main class="fix auth-layout-main">
            <div class="auth-shell">
                {{ $slot }}
            </div>
        </main>

        @include('components.layouts.landing.footer')
        @include('components.layouts.landing.footer-scripts')
        @livewireScripts
        @fluxScripts
    @include('components.layouts.live-chat')
</body>
</html>
