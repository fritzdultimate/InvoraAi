<!-- main-area -->
<main class="fix">
    <!-- banner-area -->
    <section class="banner-area banner-bg about-us-hero">
        <div class="banner-shape-wrap">
            <img src="{{ asset('new_assets/img/banner/about/banner_shape01.png') }}" alt="" class="img-one">
            <img src="{{ asset('new_assets/img/banner/about/banner_shape02.png') }}" alt="" class="img-two">
            <img src="{{ asset('new_assets/img/banner/about/banner_shape03.png') }}" alt="" class="img-three">
        </div>
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-10">
                    <div class="banner-content text-center">
                        <h2 class="title">
                            <span>INVORA AI </span> Deploys a <span>Delta-Neutral</span> Funding Yield Strategy.
                        </h2>

                        <p class="mt-3 mb-3 text-light" style="max-width: 700px; margin: auto;">
                            This is an infrastructure level trading, risk first system, not a return chasing bot. The more irrational the market, the better the system performs. Worst case outcome is flat yield, not drawdown from price moves.

                        </p>
                    </div>

                    
                </div>
            </div>
            <div class="row justify-content-center">
                <div class="col-xl-10">
                    <div class="banner-countdown-wrap text-center">

                        <div class="flex mt-4">
                            <a href="/register" class="btn btn-primary">
                                Get Started
                            </a>

                            <a href="{{ asset('new_assets/docs/INVORA DOCUMENTATION .pdf') }}" class="btn btn-outline-light ms-2 mt-2" target="_blank" rel="noopener">
                                Read Documentation / Roadmap
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
    <!-- banner-area-end -->

    @include('components.landing.about-invora-ai')

    @include('components.landing.meet-the-ceo')

    @include('components.landing.features')
    @include('components.landing.our-mission')

    @include('components.landing.our-vision')

</main>
<!-- main-area-end -->