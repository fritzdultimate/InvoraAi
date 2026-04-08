{{-- Matches roadmap: area-bg + roadmap-area; section-title + single card panel --}}
<style>
    .invora-mobile-app .section-title .title {
        color: #fff;
    }
    .invora-mobile-app-panel {
        border-radius: 20px;
        padding: clamp(1.75rem, 4vw, 2.75rem);
        background: linear-gradient(
            145deg,
            rgba(255, 255, 255, 0.07) 0%,
            rgba(255, 255, 255, 0.02) 55%,
            rgba(0, 196, 244, 0.04) 100%
        );
        border: 1px solid rgba(255, 255, 255, 0.1);
        box-shadow:
            0 4px 24px rgba(0, 0, 0, 0.2),
            inset 0 1px 0 rgba(255, 255, 255, 0.06);
    }
    .invora-mobile-app-visual {
        position: relative;
        display: inline-block;
        margin: 0 auto;
        padding: 3px;
        border-radius: 17px;
        background: linear-gradient(
            135deg,
            rgba(0, 196, 244, 0.55),
            rgba(0, 176, 139, 0.35) 45%,
            rgba(255, 255, 255, 0.12)
        );
        box-shadow: 0 12px 40px rgba(0, 0, 0, 0.25);
    }
    .invora-mobile-app-badge {
        position: absolute;
        top: 12px;
        right: 12px;
        z-index: 2;
        font-size: 0.7rem;
        font-weight: 700;
        letter-spacing: 0.08em;
        text-transform: uppercase;
        color: #0b1d33;
        background: linear-gradient(90deg, #5ee9c9, #00c4f4);
        padding: 0.35rem 0.75rem;
        border-radius: 100px;
        box-shadow: 0 4px 14px rgba(0, 0, 0, 0.25);
    }
    .invora-mobile-app__img {
        display: block;
        width: 100%;
        max-width: 300px;
        height: auto;
        margin: 0 auto;
        border-radius: 14px;
    }
    @media (min-width: 768px) {
        .invora-mobile-app__img {
            max-width: 340px;
        }
    }
    .invora-mobile-app-text {
        color: #a4b4c3;
        font-size: 1.05rem;
        line-height: 1.7;
        margin-bottom: 1rem;
    }
    .invora-mobile-app-meta {
        color: #7b8a99;
        font-size: 0.95rem;
        margin-bottom: 0;
    }
    .invora-mobile-app-meta a {
        color: #00c4f4;
        text-decoration: none;
        font-weight: 600;
        border-bottom: 1px solid rgba(0, 196, 244, 0.45);
        transition: color 0.2s ease, border-color 0.2s ease;
    }
    .invora-mobile-app-meta a:hover {
        color: #5ee9c9;
        border-bottom-color: rgba(94, 233, 201, 0.6);
    }
</style>

<div class="area-bg">
    <section id="mobile-app" class="roadmap-area invora-mobile-app pt-130 pb-130" aria-labelledby="invora-mobile-app-heading">
        <div class="container custom-container-two">
            <div class="row justify-content-center">
                <div class="col-xl-6 col-lg-8">
                    <div class="section-title text-center mb-50">
                        <span class="sub-title">Mobile</span>
                        <h2 id="invora-mobile-app-heading" class="title">
                            Native apps <span>coming soon</span>
                        </h2>
                    </div>
                </div>
            </div>

            <div class="row justify-content-center">
                <div class="col-xl-10 col-lg-11">
                    <div class="invora-mobile-app-panel">
                        <div class="row align-items-center justify-content-center g-4 g-lg-5">
                            <div class="col-md-5 text-center">
                                <div class="invora-mobile-app-visual">
                                    <span class="invora-mobile-app-badge">Coming soon</span>
                                    <img
                                        src="{{ asset('new_assets/img/images/app-2-1.png') }}"
                                        alt="{{ config('app.public_name') }} mobile app preview — iOS and Android."
                                        class="invora-mobile-app__img"
                                        loading="lazy"
                                    >
                                </div>
                            </div>
                            <div class="col-md-7 text-center text-md-start">
                                <p class="invora-mobile-app-text mb-0">
                                    Get funding, positions, and alerts in your pocket — built for the same account you use on the web, without extra logins or fragmented tools.
                                </p>
                                <p class="invora-mobile-app-meta mt-3">
                                    Listed on our <a href="{{ request()->routeIs('home-landing') ? '#roadmap' : route('home-landing') . '#roadmap' }}" class="section-link">product roadmap</a> — launch timing will be announced here.
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>
