<div class="invora-legal-page-root">
    @include('components.landing.legal-page-styles')

    <style>
        /* Robot as full-bleed hero background (no default banner_bg.html) */
        .invora-trading-bots-hero {
            position: relative;
            isolation: isolate;
            overflow: hidden;
            background: #030b15;
            padding-top: clamp(120px, 16vw, 180px);
            padding-bottom: clamp(3rem, 8vw, 5rem);
        }
        .invora-trading-bots-hero__bg {
            position: absolute;
            inset: 0;
            z-index: 0;
            background-image: url("{{ asset('new_assets/img/images/robot1.jpg') }}");
            background-position: center 20%;
            background-size: cover;
            background-repeat: no-repeat;
            transform: scale(1.02);
        }
        @media (min-width: 992px) {
            .invora-trading-bots-hero__bg {
                background-position: center center;
            }
        }
        .invora-trading-bots-hero__overlay {
            position: absolute;
            inset: 0;
            z-index: 1;
            background: linear-gradient(
                105deg,
                rgba(3, 11, 21, 0.94) 0%,
                rgba(3, 11, 21, 0.82) 38%,
                rgba(3, 11, 21, 0.45) 72%,
                rgba(3, 11, 21, 0.25) 100%
            );
        }
        .invora-trading-bots-hero__overlay::after {
            content: "";
            position: absolute;
            inset: 0;
            background: linear-gradient(0deg, rgba(3, 11, 21, 0.95) 0%, transparent 42%);
            pointer-events: none;
        }
        .invora-trading-bots-hero .invora-trading-bots-hero__inner {
            position: relative;
            z-index: 2;
        }
        .invora-trading-bots-hero .title {
            color: #e6edf3;
            text-shadow: 0 2px 24px rgba(0, 0, 0, 0.35);
        }
    </style>

    <main class="fix">
        <section class="banner-area invora-trading-bots-hero" aria-labelledby="invora-trading-bots-title">
            <div class="invora-trading-bots-hero__bg" aria-hidden="true"></div>
            <div class="invora-trading-bots-hero__overlay" aria-hidden="true"></div>
            <div class="container invora-trading-bots-hero__inner">
                <div class="row justify-content-center">
                    <div class="col-xl-8 col-lg-9 text-center text-lg-start">
                        <p class="legal-kicker mb-2" style="color: #00b08b; letter-spacing: 0.12em;">{{ config('app.public_name') }}</p>
                        <h2 id="invora-trading-bots-title" class="title mb-3" style="font-size: clamp(1.75rem, 4vw, 2.35rem);">
                            Trading <span style="color: #009A76;">bots</span>
                        </h2>
                        <p class="mb-0 text-light mx-auto mx-lg-0" style="max-width: 560px; opacity: 0.95;">
                            Pick a trading bot aligned with your capital and horizon—{{ config('app.public_name') }} runs systematic, market-neutral infrastructure around perpetual funding, not directional bets.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <x-landing.subscription-plans :show-footer-note="false" />

        <x-landing.who-we-are />

        <x-landing.how-it-works :show-intro="false" />

        <x-landing.marketing-cta />
    </main>
</div>
