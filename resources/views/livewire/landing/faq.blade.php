<div class="invora-legal-page-root">
    @include('components.landing.legal-page-styles')

    <main class="fix">
        <section class="banner-area banner-bg invora-legal-hero" aria-labelledby="invora-faq-page-title">
            <div class="banner-shape-wrap">
                <img src="{{ asset('new_assets/img/banner/banner_shape01.png') }}" alt="" class="img-one">
                <img src="{{ asset('new_assets/img/banner/banner_shape02.png') }}" alt="" class="img-two">
                <img src="{{ asset('new_assets/img/banner/banner_shape03.png') }}" alt="" class="img-three">
            </div>
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-lg-10 text-center">
                        <p class="legal-kicker mb-2" style="color: #00b08b; letter-spacing: 0.12em;">{{ config('app.public_name') }}</p>
                        <h2 id="invora-faq-page-title" class="title" style="font-size: clamp(1.75rem, 4vw, 2.35rem);">
                            Frequently Asked <span style="color: #009A76;">Questions</span>
                        </h2>
                        <p class="mt-3 mb-0 text-light" style="max-width: 640px; margin-left: auto; margin-right: auto;">
                            Straight answers on the product, risk, onboarding, and withdrawals. Binding rules and limitations are in our
                            <a href="{{ route('risk-disclosure') }}" style="color: #5ee9c9;">risk disclosure</a>
                            and
                            <a href="{{ route('terms') }}" style="color: #5ee9c9;">terms</a>.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        @include('components.landing.faq-section', [
            'accordionId' => 'faqPage',
            'firstItemOpen' => true,
        ])

        <x-landing.marketing-cta />
    </main>
</div>
