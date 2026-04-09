@php
    $name = config('app.public_name');
    $portfolioTopics = [
        [
            'q' => 'What does portfolio management mean at '.$name.'?',
            'paragraphs' => [
                'At '.$name.', portfolio management is about how your capital is allocated and supervised inside our ecosystem—not a traditional stock-and-bond mix. You choose a membership tier and deployment size; the platform applies systematic, rules-based workflows aimed at delta-neutral funding yield on perpetual futures, with visibility in your dashboard.',
            ],
        ],
        [
            'q' => 'How is market exposure controlled?',
            'paragraphs' => [
                $name.' is built around market-neutral positioning: paired long and short legs sized to keep net price exposure at or near zero, so outcomes are driven primarily by funding flows and structure—not by betting on whether spot moves up or down.',
            ],
        ],
        [
            'q' => 'How do membership tiers relate to your allocation?',
            'paragraphs' => [
                'Tiers (for example Smart, Brilliant, and Genius) combine a license term, indicative yield band, and minimum capital. They let you match commitment level and horizon; details and live numbers are always shown in-app when you select or renew a plan.',
            ],
        ],
        [
            'q' => 'What can you monitor in the dashboard?',
            'paragraphs' => [
                'You can review balances, funding-related outcomes where applicable, status of automated workflows, and other allocation-relevant activity so you can reconcile what the system did with your own expectations. Transparency focuses on structure and funding, not opaque discretionary calls.',
            ],
        ],
        [
            'q' => 'Risk and responsibility',
            'paragraphs' => [
                $name.' uses strict risk management systems, including capital allocation controls and automated safeguards, to help protect user funds where the platform can influence process. However, like all trading, returns are not guaranteed.',
                'The platform is risk-first: position and margin constraints, exchange rules, liquidation risk, and operational safeguards apply. Funding can change sign or magnitude, and markets can gap. Funding rates, margin, liquidity, and venue rules can all change; no platform removes market, counterparty, or technology risk. Read our risk disclosure and terms before you deploy capital.',
            ],
        ],
        [
            'q' => 'How do you get started?',
            'paragraphs' => [
                'Create an account, complete onboarding, pick the tier that fits your capital and horizon, and fund through the supported rails shown in-app. For a broader picture of how '.$name.' fits together, see the full Invora AI overview or our How it works page.',
            ],
        ],
    ];
@endphp

<div class="invora-legal-page-root">
    @include('components.landing.legal-page-styles')

    <style>
        .invora-pm-accordion {
            margin-top: 1.5rem;
        }
        .invora-pm-accordion details {
            border: 1px solid rgba(157, 188, 212, 0.14);
            border-radius: 14px;
            background: rgba(6, 16, 28, 0.55);
            margin-bottom: 0.65rem;
            overflow: hidden;
        }
        .invora-pm-accordion details[open] {
            border-color: rgba(0, 176, 139, 0.28);
        }
        .invora-pm-accordion summary {
            list-style: none;
            cursor: pointer;
            padding: 1rem 1.15rem 1rem 3rem;
            position: relative;
            font-weight: 600;
            font-size: 1rem;
            color: #dbe8f4;
            line-height: 1.4;
        }
        .invora-pm-accordion summary::-webkit-details-marker {
            display: none;
        }
        .invora-pm-accordion summary::before {
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
        .invora-pm-accordion .invora-pm-accordion__panel {
            padding: 0 1.15rem 1.1rem 3rem;
            color: #a4b4c3;
            font-size: 0.98rem;
            line-height: 1.7;
        }
        .invora-pm-accordion .invora-pm-accordion__panel p:last-child {
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
                            Portfolio <span style="color: #009A76;">management</span>
                        </h2>
                        <p class="mt-3 mb-0 text-light" style="max-width: 700px; margin-left: auto; margin-right: auto;">
                            How {{ config('app.public_name') }} helps you align capital, membership tiers, and visibility—inside a <strong class="fw-semibold text-white">delta-neutral, funding-first</strong> framework on perpetual futures.
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
                            <h1 class="legal-title">How we think about your portfolio</h1>
                            <p class="legal-lead mb-0">
                                These notes explain allocation, exposure, tiers, and monitoring in plain language. They are general information only, not personalized advice. For binding terms and risks, use our
                                <a href="{{ route('terms') }}">terms</a>,
                                <a href="{{ route('risk-disclosure') }}">risk disclosure</a>,
                                and in-app controls. For a deeper product narrative, read the
                                <a href="{{ asset('new_assets/docs/INVORA DOCUMENTATION .pdf') }}" target="_blank" rel="noopener">full Invora AI overview</a>.
                            </p>

                            <div class="invora-pm-accordion">
                                @foreach ($portfolioTopics as $index => $topic)
                                    <details @if ($index === 0) open @endif>
                                        <summary data-step="{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}">
                                            {{ $topic['q'] }}
                                            </summary>
                                        <div class="invora-pm-accordion__panel">
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
                                <a href="{{ route('trading-bots') }}">Trading bots</a>
                                <span class="mx-2">·</span>
                                <a href="{{ route('trading-execution') }}">Trading execution</a>
                                <span class="mx-2">·</span>
                                <a href="{{ route('trading-guidelines') }}">Trading guidelines</a>
                                <span class="mx-2">·</span>
                                <a href="{{ route('how-it-works') }}">How it works</a>
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
