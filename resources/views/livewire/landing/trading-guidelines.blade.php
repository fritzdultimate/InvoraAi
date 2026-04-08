@php
    $guidelineTopics = [
        [
            'q' => 'What is the core idea: “monetizing leverage, not price”?',
            'paragraphs' => [
                config('app.public_name').' is a global, AI-driven, market-neutral trading ecosystem. We focus on monetizing leverage and funding flows in perpetual futures—not on predicting spot direction. There is no bullish or bearish bias, no price prediction, and no emotional trading; yield comes from structural conditions and funding paid by leveraged participants.',
            ],
        ],
        [
            'q' => 'What market context does '.config('app.public_name').' focus on?',
            'paragraphs' => [
                'Perpetual futures sit at the center of crypto trading, with a large share of volume leveraged. Many participants pay funding; '.config('app.public_name').' is built as infrastructure to collect funding in a neutral way, while many retail approaches still depend on direction, indicators, or narratives.',
            ],
        ],
        [
            'q' => 'How does '.config('app.public_name').' deploy the strategy?',
            'paragraphs' => [
                config('app.public_name').' uses a delta-neutral funding yield strategy: simultaneous long and short positions, equal size and leverage, and zero or near-zero net price exposure. Returns are driven by funding flows, not by directional price movement—the design is market-neutral and regime-agnostic.',
            ],
        ],
        [
            'q' => 'Who operates the platform?',
            'paragraphs' => [
                config('app.public_name').' is developed and operated by INVORA CAPITAL LTD, an approved investment manager regulated under the British Virgin Islands Financial Services Commission (BVI FSC), with real-time funding intelligence, delta-neutral execution, and institutional-grade risk controls.',
            ],
        ],
        [
            'q' => 'What security and safety measures does '.config('app.public_name').' use?',
            'paragraphs' => [
                'We prioritize user safety and transparency: cold storage for company reserve assets, multi-signature wallets, SSL encryption, two-factor authentication (2FA), regulatory compliance, AML vigilance, 24/7 monitoring, and incident response readiness. Confirm the latest details in-app as we update systems.',
            ],
        ],
        [
            'q' => 'What does “funding-first transparency” mean?',
            'paragraphs' => [
                'At '.config('app.public_name').', transparency means clear visibility into position structure, funding receipts, and risk exposure—not opaque trading narratives. Members can see paired long/short views, net exposure near zero, live funding rates, and funding settlement timing in the dashboard.',
            ],
        ],
        [
            'q' => 'What membership tiers does '.config('app.public_name').' offer?',
            'paragraphs' => [
                'We offer tiered bot licenses—for example Invora Smart ($25 / 3 months, up to ~0.70% daily indicative yield, $299 capital), Invora Brilliant ($50 / 6 months, up to ~1.10% daily, $4,999 capital), and Invora Genius ($100 / annual, up to ~1.30% daily, $19,999 capital). Features and accrual cadence (e.g. every six hours) vary by tier; use the membership area in-app for live tables.',
            ],
        ],
        [
            'q' => 'What are withdrawal and lockup rules?',
            'paragraphs' => [
                config('app.public_name').' applies a 90-day capital lockup for seed capital, with an early-withdrawal deduction (15% in the published example), a 2% processing fee on withdrawals, a $10 minimum withdrawal, and rules for new deposits that can start a fresh lock where applicable. Always confirm current numbers in-app and in the latest legal terms—they can change.',
            ],
        ],
        [
            'q' => 'Compliance and disclaimers',
            'paragraphs' => [
                config('app.public_name').' uses strict risk management systems, including capital allocation controls and automated safeguards, to help protect user funds where the platform can influence process. However, like all trading, returns are not guaranteed. The platform is risk-first: position and margin constraints, exchange rules, liquidation risk, and operational safeguards apply. Funding can change sign or magnitude, and markets can gap.',
                'Information from '.config('app.public_name').' is educational, not financial advice; outcomes depend on your situation and market conditions. Read our risk disclosure and terms for binding language. Never allocate more than you can afford to lose, and comply with laws where you live.',
            ],
        ],
    ];
@endphp

<div class="invora-legal-page-root">
    @include('components.landing.legal-page-styles')

    <style>
        .invora-guidelines-accordion {
            margin-top: 1.5rem;
        }
        .invora-guidelines-accordion details {
            border: 1px solid rgba(157, 188, 212, 0.14);
            border-radius: 14px;
            background: rgba(6, 16, 28, 0.55);
            margin-bottom: 0.65rem;
            overflow: hidden;
        }
        .invora-guidelines-accordion details[open] {
            border-color: rgba(0, 176, 139, 0.28);
        }
        .invora-guidelines-accordion summary {
            list-style: none;
            cursor: pointer;
            padding: 1rem 1.15rem 1rem 3rem;
            position: relative;
            font-weight: 600;
            font-size: 1rem;
            color: #dbe8f4;
            line-height: 1.4;
        }
        .invora-guidelines-accordion summary::-webkit-details-marker {
            display: none;
        }
        .invora-guidelines-accordion summary::before {
            content: attr(data-step);
            position: absolute;
            left: 1rem;
            top: 50%;
            transform: translateY(-50%);
            font-size: 0.72rem;
            font-weight: 700;
            letter-spacing: 0.08em;
            color: #00b08b;
        }
        .invora-guidelines-accordion .invora-guidelines-accordion__panel {
            padding: 0 1.15rem 1.1rem 3rem;
            color: #a4b4c3;
            font-size: 0.98rem;
            line-height: 1.7;
        }
        .invora-guidelines-accordion .invora-guidelines-accordion__panel p:last-child {
            margin-bottom: 0;
        }
    </style>

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
                            Trading <span style="color: #009A76;">guidelines</span>
                        </h2>
                        <p class="mt-3 mb-0 text-light" style="max-width: 720px; margin-left: auto; margin-right: auto;">
                            How {{ config('app.public_name') }} applies market-neutral, funding-first infrastructure—and the operational rules we apply on the platform.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <section class="invora-legal-wrap pt-70 pb-110">
            <div class="container">
                <div class="row justify-content-center">
                    <div class="col-xl-9 col-lg-10">
                        <article class="invora-legal-card">
                            <p class="legal-kicker">{{ config('app.public_name') }}</p>
                            <h1 class="legal-title">Guidelines overview</h1>
                            <p class="legal-lead mb-0">
                                The accordion below summarizes how {{ config('app.public_name') }} approaches delta-neutral funding yield, regulation, transparency, membership tiers, and withdrawal rules. This is a readable overview, not a legal contract—always follow the
                                <a href="{{ asset('new_assets/docs/INVORA DOCUMENTATION .pdf') }}" target="_blank" rel="noopener">full Invora AI overview</a>,
                                in-app settings, and our
                                <a href="{{ route('terms') }}">terms</a>
                                /
                                <a href="{{ route('risk-disclosure') }}">risk disclosure</a>
                                for binding terms.
                            </p>

                            <div class="invora-guidelines-accordion">
                                @foreach ($guidelineTopics as $index => $topic)
                                    <details @if ($index === 0) open @endif>
                                        <summary data-step="{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}">
                                            {{ $topic['q'] }}
                                            </summary>
                                        <div class="invora-guidelines-accordion__panel">
                                            @foreach ($topic['paragraphs'] as $p)
                                                <p>{{ $p }}</p>
                                            @endforeach
                                            </div>
                                        </details>
                                @endforeach
                                                    </div>

                            <p class="mb-0 mt-4">
                                Questions? Email
                                <a href="mailto:{{ env('SUPPORT_EMAIL', 'support@invora.ai') }}">{{ env('SUPPORT_EMAIL', 'support@invora.ai') }}</a>.
                            </p>

                            <div class="invora-legal-related">
                                Related:
                                <a href="{{ asset('new_assets/docs/INVORA DOCUMENTATION .pdf') }}" target="_blank" rel="noopener">Full Invora AI overview</a>
                                <span class="mx-2">·</span>
                                <a href="{{ route('trading-execution') }}">Trading execution</a>
                                <span class="mx-2">·</span>
                                <a href="{{ route('trading-bots') }}">Trading bots</a>
                                <span class="mx-2">·</span>
                                <a href="{{ route('risk-disclosure') }}">Risk disclosure</a>
                                <span class="mx-2">·</span>
                                <a href="{{ route('contact-us') }}">Contact us</a>
                            </div>
                        </article>
                    </div>
                </div>
            </div>
        </section>
    </main>
</div>
