{{-- Invora trading bot tiers: member cost, daily yield target, capital required --}}
                @php
                    $plans = [
                        [
                            'id' => 212,
            'name' => 'Invora Smart Bot',
            'image' => 'assets/images/bot/invora-smart-bot.png',
            'member_tier_cost' => '$25 / 3 months',
            'daily_yield' => 'Up to 0.70% daily',
            'capital_required' => '$299',
                            'popular' => false,
                            'features' => [
                'Market-neutral systematic execution',
                'Dashboard access & performance visibility',
                'Suited for smaller allocations',
                            ],
                        ],
                        [
                            'id' => 432,
            'name' => 'Invora Brilliant Bot',
            'image' => 'assets/images/bot/invora-brilliant-bot.png',
            'member_tier_cost' => '$50 / 6 months',
            'daily_yield' => 'Up to 1.10% daily',
            'capital_required' => '$4,999',
                            'popular' => true,
                            'features' => [
                'Higher tier funding-yield focus',
                'Priority execution profile vs Smart',
                'Extended membership window',
                            ],
                        ],
                        [
                            'id' => 4934,
            'name' => 'Invora Genius Bot',
            'image' => 'assets/images/bot/invora-genius-bot.png',
            'member_tier_cost' => '$100 / annual',
            'daily_yield' => 'Up to 1.30% daily',
            'capital_required' => '$19,999',
                            'popular' => false,
                            'features' => [
                'Top-tier parameters & longest membership',
                'Designed for larger capital deployment',
                'Best for experienced participants',
                            ],
                        ],
                    ];
                @endphp

<style>
    .invora-plans-area {
        background: linear-gradient(180deg, #030b15 0%, #071510 48%, #030b15 100%);
        padding-top: clamp(3rem, 6vw, 4.5rem);
        padding-bottom: clamp(3.5rem, 7vw, 5.5rem);
    }
    .invora-plans-area .section-title .sub-title {
        color: #00b08b;
        letter-spacing: 0.12em;
    }
    .invora-plan-card {
        position: relative;
        height: 100%;
        display: flex;
        flex-direction: column;
        border: 1px solid rgba(157, 188, 212, 0.14);
        border-radius: 20px;
        background: linear-gradient(165deg, rgba(13, 27, 44, 0.72) 0%, rgba(6, 16, 28, 0.92) 100%);
        box-shadow: 0 24px 56px rgba(0, 0, 0, 0.3);
        overflow: hidden;
        transition: border-color 0.2s ease, box-shadow 0.2s ease;
    }
    .invora-plan-card:hover {
        border-color: rgba(0, 176, 139, 0.3);
    }
    .invora-plan-card--popular {
        border-color: rgba(0, 176, 139, 0.45);
        box-shadow: 0 28px 64px rgba(0, 154, 118, 0.15);
    }
    .invora-plan-card__badge {
        position: absolute;
        top: 1rem;
        right: 1rem;
        z-index: 2;
        font-size: 0.68rem;
        font-weight: 700;
        letter-spacing: 0.12em;
        text-transform: uppercase;
        color: #030b15;
        background: linear-gradient(135deg, #5ee9c9 0%, #00b08b 100%);
        padding: 0.35rem 0.75rem;
        border-radius: 999px;
    }
    .invora-plan-card__img-wrap {
        background: linear-gradient(180deg, rgba(0, 40, 32, 0.35) 0%, transparent 100%);
        padding: 1.25rem 1.25rem 0.5rem;
        text-align: center;
    }
    .invora-plan-card__img {
        max-height: 160px;
        width: auto;
        max-width: 100%;
        object-fit: contain;
    }
    .invora-plan-card__body {
        padding: 0 1.5rem 1.5rem;
        flex: 1;
        display: flex;
        flex-direction: column;
    }
    .invora-plan-card__name {
        color: #e6edf3;
        font-size: 1.15rem;
        font-weight: 700;
        margin-bottom: 1rem;
        line-height: 1.3;
    }
    .invora-plan-card__table {
        width: 100%;
        border-collapse: collapse;
        font-size: 0.88rem;
        margin-bottom: 1.25rem;
    }
    .invora-plan-card__table th,
    .invora-plan-card__table td {
        padding: 0.5rem 0;
        text-align: left;
        vertical-align: top;
        border-bottom: 1px solid rgba(157, 188, 212, 0.1);
    }
    .invora-plan-card__table th {
        color: #8fa3b5;
        font-weight: 600;
        width: 48%;
    }
    .invora-plan-card__table td {
        color: #dbe8f4;
        font-weight: 600;
    }
    .invora-plan-card__table tr:last-child th,
    .invora-plan-card__table tr:last-child td {
        border-bottom: none;
    }
    .invora-plan-card__features {
        list-style: none;
        padding: 0;
        margin: 0 0 1.25rem;
        flex: 1;
    }
    .invora-plan-card__features li {
        position: relative;
        padding-left: 1.25rem;
        margin-bottom: 0.5rem;
        font-size: 0.9rem;
        color: #a4b4c3;
        line-height: 1.5;
    }
    .invora-plan-card__features li::before {
        content: "";
        position: absolute;
        left: 0;
        top: 0.45em;
        width: 6px;
        height: 6px;
        border-radius: 50%;
        background: #00b08b;
    }
    .invora-plan-card__btn {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        width: 100%;
        padding: 0.9rem 1.25rem;
        border-radius: 999px;
        font-size: 0.82rem;
        font-weight: 700;
        letter-spacing: 0.06em;
        text-transform: uppercase;
        text-decoration: none;
        color: #fff !important;
        background: linear-gradient(135deg, #009A76 0%, #007a62 100%);
        border: 1px solid rgba(0, 201, 154, 0.4);
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .invora-plan-card__btn:hover {
        color: #fff !important;
        transform: translateY(-2px);
        box-shadow: 0 10px 28px rgba(0, 154, 118, 0.35);
    }
    .invora-plan-card__note {
        font-size: 0.72rem;
        color: #7a8d9e;
        margin-top: 0.75rem;
        line-height: 1.45;
        text-align: center;
    }
</style>

<section id="subscription" class="invora-plans-area" aria-labelledby="invora-plans-heading">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-8 col-lg-10">
                <div class="section-title text-center mb-40">
                    <span class="sub-title">Member tiers</span>
                    <h2 id="invora-plans-heading" class="title">
                        {{ config('app.public_name') }} <span>trading bots</span>
                    </h2>
                    <p class="mt-3 mb-0 mx-auto" style="max-width: 640px; color: #9bb2c6; font-size: 1rem; line-height: 1.65;">
                        Compare member tier cost, indicative daily yield, and minimum capital. Yields are not guaranteed; see our
                        <a href="{{ route('risk-disclosure') }}" style="color: #5ee9c9;">risk disclosure</a>.
                    </p>
                                    </div>
                                </div>
                            </div>

        <div class="row g-4 justify-content-center">
            @foreach ($plans as $plan)
                <div class="col-lg-4 col-md-6">
                    <article class="invora-plan-card @if ($plan['popular']) invora-plan-card--popular @endif">
                        @if ($plan['popular'])
                            <span class="invora-plan-card__badge">Most popular</span>
                        @endif
                        <div class="invora-plan-card__img-wrap">
                            <img
                                src="{{ asset($plan['image']) }}"
                                alt="{{ $plan['name'] }}"
                                class="invora-plan-card__img"
                                loading="lazy"
                                width="280"
                                height="160"
                            >
                                    </div>
                        <div class="invora-plan-card__body">
                            <h3 class="invora-plan-card__name">{{ $plan['name'] }}</h3>

                            <table class="invora-plan-card__table">
                                <tbody>
                                    <tr>
                                        <th scope="row">Member tier cost</th>
                                        <td>{{ $plan['member_tier_cost'] }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Daily yield</th>
                                        <td>{{ $plan['daily_yield'] }}</td>
                                    </tr>
                                    <tr>
                                        <th scope="row">Capital required</th>
                                        <td>{{ $plan['capital_required'] }}</td>
                                    </tr>
                                </tbody>
                            </table>

                            <ul class="invora-plan-card__features">
                                @foreach ($plan['features'] as $feature)
                                    <li>{{ $feature }}</li>
                                            @endforeach
                                        </ul>

                            <a href="{{ route('register') }}" class="invora-plan-card__btn">Get started</a>
                            <p class="invora-plan-card__note">Subject to availability, verification, and product rules in-app.</p>
                        </div>
                    </article>
                </div>
            @endforeach
        </div>
    </div>
</section>
