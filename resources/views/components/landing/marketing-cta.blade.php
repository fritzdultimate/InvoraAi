<style>
    .invora-marketing-cta {
        position: relative;
        padding: clamp(3.25rem, 8vw, 5.5rem) 0;
        overflow: hidden;
        background: radial-gradient(ellipse 80% 60% at 50% 0%, rgba(0, 176, 139, 0.12) 0%, transparent 55%),
            linear-gradient(180deg, #030b15 0%, #020810 100%);
    }
    .invora-marketing-cta::before {
        content: "";
        position: absolute;
        left: 50%;
        top: 0;
        transform: translateX(-50%);
        width: min(720px, 90%);
        height: 1px;
        background: linear-gradient(90deg, transparent, rgba(0, 176, 139, 0.35), transparent);
    }
    .invora-marketing-cta__panel {
        max-width: 720px;
        margin: 0 auto;
        padding: clamp(2rem, 4vw, 2.75rem) clamp(1.5rem, 4vw, 2.5rem);
        text-align: center;
        border: 1px solid rgba(157, 188, 212, 0.14);
        border-radius: 24px;
        background: linear-gradient(165deg, rgba(13, 27, 44, 0.55) 0%, rgba(6, 16, 28, 0.85) 100%);
        box-shadow: 0 24px 64px rgba(0, 0, 0, 0.35), inset 0 1px 0 rgba(255, 255, 255, 0.04);
    }
    .invora-marketing-cta__kicker {
        font-size: 0.78rem;
        font-weight: 600;
        letter-spacing: 0.16em;
        text-transform: uppercase;
        color: #00b08b;
        margin-bottom: 1rem;
    }
    .invora-marketing-cta__title {
        color: #e6edf3;
        font-size: clamp(1.35rem, 3.2vw, 1.85rem);
        font-weight: 700;
        line-height: 1.28;
        margin: 0 0 1.1rem;
        letter-spacing: -0.02em;
    }
    .invora-marketing-cta__title .invora-marketing-cta__accent {
        color: #5ee9c9;
    }
    .invora-marketing-cta__title .invora-marketing-cta__sub {
        display: block;
        margin-top: 0.35rem;
        font-size: 0.92em;
        font-weight: 600;
        color: #8fa3b5;
        letter-spacing: 0;
    }
    .invora-marketing-cta__lead {
        color: #a4b4c3;
        font-size: clamp(0.95rem, 1.6vw, 1.05rem);
        line-height: 1.7;
        margin: 0 auto 0.85rem;
        max-width: 34em;
    }
    .invora-marketing-cta__disclaimer {
        color: #7a8d9e;
        font-size: 0.78rem;
        line-height: 1.55;
        margin: 0 auto 1.65rem;
        max-width: 36em;
    }
    .invora-marketing-cta__disclaimer a {
        color: #8fa3b5;
        text-underline-offset: 2px;
    }
    .invora-marketing-cta__disclaimer a:hover {
        color: #5ee9c9;
    }
    .invora-marketing-cta__btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        padding: 1rem 2rem;
        border-radius: 999px;
        font-size: 0.9rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        text-decoration: none;
        color: #fff !important;
        background: linear-gradient(135deg, #009A76 0%, #007a62 100%);
        border: 1px solid rgba(0, 201, 154, 0.45);
        box-shadow: 0 10px 32px rgba(0, 154, 118, 0.35);
        transition: transform 0.2s ease, box-shadow 0.2s ease, background 0.2s ease;
    }
    .invora-marketing-cta__btn:hover {
        color: #fff !important;
        transform: translateY(-2px);
        box-shadow: 0 14px 40px rgba(0, 154, 118, 0.45);
        background: linear-gradient(135deg, #00b08b 0%, #009A76 100%);
    }
    .invora-marketing-cta__btn svg {
        width: 1em;
        height: 1em;
        opacity: 0.9;
    }
</style>

<section class="invora-marketing-cta" aria-labelledby="invora-marketing-cta-heading">
    <div class="container">
        <div class="invora-marketing-cta__panel">
            <p class="invora-marketing-cta__kicker">Why {{ config('app.public_name') }}</p>
            <h2 id="invora-marketing-cta-heading" class="invora-marketing-cta__title">
                Systematic infrastructure for <span class="invora-marketing-cta__accent">funding yield</span><span class="invora-marketing-cta__sub">—not directional bets</span>
                    </h2>
            <p class="invora-marketing-cta__lead">
                {{ config('app.public_name') }} is built around balanced exposure and automated execution on perpetual funding—not on guessing whether prices go up or down. Create your account, choose a setup that fits your goals, and go live in minutes.
            </p>
            <p class="invora-marketing-cta__disclaimer">
                Crypto strategies involve risk. Read our
                <a href="{{ route('risk-disclosure') }}">risk disclosure</a>
                before you allocate funds.
            </p>
            <a href="{{ route('register') }}" class="invora-marketing-cta__btn">
                Get started
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 448 512" fill="currentColor" aria-hidden="true">
                    <path d="M190.5 66.9l22.2-22.2c9.4-9.4 24.6-9.4 33.9 0L441 239c9.4 9.4 9.4 24.6 0 33.9L246.6 467.3c-9.4 9.4-24.6 9.4-33.9 0l-22.2-22.2c-9.5-9.5-9.3-25 .4-34.3L311.4 296H24c-13.3 0-24-10.7-24-24v-32c0-13.3 10.7-24 24-24h287.4L190.9 101.2c-9.8-9.3-10-24.8-.4-34.3z"/>
                </svg>
            </a>
        </div>
    </div>
</section>
