<div class="invora-legal-page-root">
    @include('components.landing.legal-page-styles')

    <main class="fix">
        <section class="banner-area banner-bg invora-legal-hero">
            <div class="banner-shape-wrap">
                <img src="{{ asset('new_assets/img/banner/banner_shape01.png') }}" alt="" class="img-one">
                <img src="{{ asset('new_assets/img/banner/banner_shape02.png') }}" alt="" class="img-two">
                <img src="{{ asset('new_assets/img/banner/banner_shape03.png') }}" alt="" class="img-three">
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10 text-center">
                        <p class="legal-kicker mb-2" style="color: #00b08b; letter-spacing: 0.12em;">Platform</p>
                        <h2 class="title" style="font-size: clamp(1.75rem, 4vw, 2.35rem);">
                            How it <span style="color: #009A76;">works</span>
                        </h2>
                        <p class="mt-3 mb-0 text-light" style="max-width: 680px; margin-left: auto; margin-right: auto;">
                            From signup to monitoring your strategy—{{ config('app.public_name') }} walks you through a transparent, systematic path built around market-neutral infrastructure, not chasing price direction.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <x-landing.how-it-works :show-intro="false" />

        <x-landing.who-we-are />

        <x-landing.features />

        <x-landing.marketing-cta />
    </main>
</div>
