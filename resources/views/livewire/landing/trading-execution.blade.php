@php
    $executionTopics = [
        [
            'q' => 'How does '.config('app.public_name').' decide when to act?',
            'a' => config('app.public_name').' is built to capture funding flows in perpetual futures—not to forecast spot direction for speculative gain. Execution responds to structural inputs such as funding rates, basis, spreads, margin requirements, and venue liquidity, so automated workflows stay aligned with Invora AI’s delta-neutral, funding-yield design.',
        ],
        [
            'q' => 'What does “execution” mean in this model?',
            'a' => 'Execution is infrastructure-level and rules-based: the system opens, adjusts, and maintains hedged exposure consistent with the parameters you choose, aiming to stay on the receiving side of funding fees paid by leveraged participants—Invora AI’s “funding capture, not forecasts” approach. It is systematic automation, not discretionary manual trading on your behalf.',
        ],
        [
            'q' => 'How is risk managed during execution?',
            'a' => 'The platform is risk-first: position and margin constraints, exchange rules, liquidation risk, and operational safeguards apply. Funding can change sign or magnitude, and markets can gap—see our risk disclosure if you want more detail.',
        ],
        [
            'q' => 'How can I review what happened?',
            'a' => 'Your dashboard shows allocation-relevant activity—balances, funding-related outcomes where applicable, and status of automated workflows—so you can review and reconcile. Yields and outcomes are scenario-dependent; past activity does not guarantee future results.',
        ],
    ];
@endphp

<div class="invora-legal-page-root">
    @include('components.landing.legal-page-styles')

<style>
        .invora-exec-accordion {
            margin-top: 1.5rem;
        }
        .invora-exec-accordion details {
            border: 1px solid rgba(157, 188, 212, 0.14);
            border-radius: 14px;
            background: rgba(6, 16, 28, 0.55);
            margin-bottom: 0.65rem;
            overflow: hidden;
        }
        .invora-exec-accordion details[open] {
            border-color: rgba(0, 176, 139, 0.28);
        }
        .invora-exec-accordion summary {
            list-style: none;
            cursor: pointer;
            padding: 1rem 1.15rem 1rem 3rem;
            position: relative;
            font-weight: 600;
            font-size: 1rem;
            color: #dbe8f4;
            line-height: 1.4;
        }
        .invora-exec-accordion summary::-webkit-details-marker {
            display: none;
        }
        .invora-exec-accordion summary::before {
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
        .invora-exec-accordion .invora-exec-accordion__panel {
            padding: 0 1.15rem 1.1rem 3rem;
            color: #a4b4c3;
            font-size: 0.98rem;
            line-height: 1.7;
        }
        .invora-exec-accordion .invora-exec-accordion__panel p:last-child {
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
                            Trading <span style="color: #009A76;">execution</span>
                        </h2>
                        <p class="mt-3 mb-0 text-light" style="max-width: 680px; margin-left: auto; margin-right: auto;">
                            How {{ config('app.public_name') }} runs infrastructure-level, risk-first execution around a <strong class="fw-semibold text-white">delta-neutral funding yield</strong> strategy on perpetual futures—funding capture, not price forecasts.
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
                            <h1 class="legal-title">Key execution factors</h1>
                            <p class="legal-lead mb-0">
                                The points below reflect how {{ config('app.public_name') }} frames execution: a <strong>delta-neutral</strong>, <strong>funding-yield</strong> approach on <strong>perpetual futures</strong>—infrastructure-level and <strong>risk-first</strong>, not a return-chasing bot. This is general information only, not personalized advice. For more detail, read the
                                <a href="{{ asset('new_assets/docs/INVORA DOCUMENTATION .pdf') }}" target="_blank" rel="noopener">full Invora AI overview</a>.
                            </p>

                            <div class="invora-exec-accordion">
                                @foreach ($executionTopics as $index => $topic)
                                    <details @if ($index === 0) open @endif>
                                        <summary data-step="{{ str_pad((string) ($index + 1), 2, '0', STR_PAD_LEFT) }}">
                                            {{ $topic['q'] }}
                                            </summary>
                                        <div class="invora-exec-accordion__panel">
                                            <p>{{ $topic['a'] }}</p>
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
                                <a href="{{ route('how-it-works') }}">How it works</a>
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
