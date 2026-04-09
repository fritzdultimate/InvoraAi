@props([
    'showIntro' => true,
])

<style>
    .invora-how-cards-wrap {
        background: linear-gradient(180deg, rgba(3, 11, 21, 0.98) 0%, #030b15 48%, #030b15 100%);
        padding-bottom: clamp(3rem, 8vw, 5.5rem);
    }
    .invora-how-card {
        height: 100%;
        border: 1px solid rgba(157, 188, 212, 0.14);
        border-radius: 20px;
        background: linear-gradient(165deg, rgba(13, 27, 44, 0.72) 0%, rgba(6, 16, 28, 0.88) 100%);
        box-shadow: 0 20px 48px rgba(0, 0, 0, 0.28);
        padding: clamp(1.35rem, 2.5vw, 1.85rem);
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .invora-how-card:hover {
        border-color: rgba(0, 176, 139, 0.28);
        box-shadow: 0 24px 56px rgba(0, 0, 0, 0.32);
    }
    .invora-how-card__step {
        display: inline-block;
        font-size: 0.75rem;
        font-weight: 700;
        letter-spacing: 0.14em;
        color: #00b08b;
        margin-bottom: 0.65rem;
    }
    .invora-how-card__title {
        color: #e6edf3;
        font-size: clamp(1.05rem, 2vw, 1.2rem);
        font-weight: 700;
        line-height: 1.3;
        margin-bottom: 0.75rem;
    }
    .invora-how-card__text {
        color: #a4b4c3;
        font-size: 0.95rem;
        line-height: 1.65;
        margin-bottom: 0;
    }
</style>

<div @class(['pt-70' => ! $showIntro])>

    @if ($showIntro)
        <div data-elementor-type="wp-page" data-elementor-id="295" class="elementor elementor-295"
            data-elementor-post-type="page">
            <div class="elementor-element elementor-element-5c24357 e-flex e-con-boxed e-con e-parent" data-id="5c24357"
                data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
                <div class="e-con-inner">
                    <div class="elementor-element elementor-element-0565c22 e-flex e-con-boxed e-con e-child"
                        data-id="0565c22" data-element_type="container">
                        <div class="e-con-inner">
                            <div class="elementor-element elementor-element-b1fc91f elementor-invisible elementor-widget elementor-widget-heading"
                                data-id="b1fc91f" data-element_type="widget"
                                data-settings="{&quot;_animation_delay&quot;:200,&quot;_animation&quot;:&quot;fadeInUp&quot;}"
                                data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                    <h6 class="elementor-heading-title elementor-size-default">HOW IT WORKS</h6>
                                </div>
                            </div>
                            <div class="elementor-element elementor-element-f8d3e0b elementor-invisible elementor-widget elementor-widget-heading"
                                data-id="f8d3e0b" data-element_type="widget"
                                data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300}"
                                data-widget_type="heading.default">
                                <div class="elementor-widget-container">
                                    <h1 class="elementor-heading-title elementor-size-default">
                                        Four steps from account setup to monitoring your funding-yield infrastructure—systematic execution, not guessing market direction.
                                    </h1>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif

    <section @class(['invora-how-cards-wrap', 'pt-50' => $showIntro]) aria-label="How it works steps">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-6">
                    <article class="invora-how-card">
                        <span class="invora-how-card__step">01</span>
                        <h3 class="invora-how-card__title">Create account &amp; setup</h3>
                        <p class="invora-how-card__text">
                            Create your account, complete onboarding, and prepare to earn from the market-neutral systematic approach {{ config('app.public_name') }} is built for.
                        </p>
                    </article>
                </div>
                <div class="col-md-6">
                    <article class="invora-how-card">
                        <span class="invora-how-card__step">02</span>
                        <h3 class="invora-how-card__title">Fund &amp; activate</h3>
                        <p class="invora-how-card__text">
                            Deposit supported assets at an amount that works for you, choose your trading bot, and activate it. Funding follows the product rules shown in-app—secure flows, clear status, and no hidden manual steps.
                        </p>
                    </article>
                </div>
                <div class="col-md-6">
                    <article class="invora-how-card">
                        <span class="invora-how-card__step">03</span>
                        <h3 class="invora-how-card__title">System runs 24/7</h3>
                        <p class="invora-how-card__text">
                            The engine monitors conditions and manages positions and funding-related mechanics automatically—so you are not required to time entries or predict whether spot prices move up or down.
                        </p>
                    </article>
                </div>
                <div class="col-md-6">
                    <article class="invora-how-card">
                        <span class="invora-how-card__step">04</span>
                        <h3 class="invora-how-card__title">Monitor &amp; withdraw</h3>
                        <p class="invora-how-card__text">
                            Review performance, funding activity, and balances in your dashboard; withdraw or adjust according to the rules of your plan. Yields vary—past results are not a guarantee of future returns.
                        </p>
                    </article>
                </div>
            </div>
        </div>
    </section>
</div>
