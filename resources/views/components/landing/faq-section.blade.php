{{-- FAQ accordion. Optional: $accordionId, $firstItemOpen when including. --}}
@php
    $accordionId = $accordionId ?? 'invoraFaqAccordion';
    $firstItemOpen = $firstItemOpen ?? true;
    $name = config('app.public_name');
    $items = [
        [
            'q' => 'What is '.$name.'?',
            'type' => 'text',
            'text' => $name.' is a market-neutral, AI-powered trading system designed to generate consistent yield from crypto derivatives. Instead of predicting market direction, it captures funding fees from leveraged traders using balanced long and short positions, eliminating exposure to price volatility.  Built on institutional-grade infrastructure, Invora focuses on transparency, risk management, and sustainable, data-driven returns.',
        ],
        [
            'q' => 'How does the AI generate returns?',
            'type' => 'text',
            'text' => 'The system monitors market data in real-time and uses market-neutral strategies, combining long and short positions, to take advantage of price inefficiencies while reducing exposure to market direction.',
        ],
        [
            'q' => 'Is my trades safe?',
            'type' => 'risk',
        ],
        [
            'q' => 'Do I need trading experience to use '.$name.'?',
            'type' => 'text',
            'text' => 'No. The platform is designed for both beginners and experienced traders. The AI handles complex trading decisions while you monitor your trading performance.',
        ],
        [
            'q' => 'How can I start trading with AI?',
            'type' => 'text',
            'text' => 'Simply create an account, choose a trading bot that fits your strategy, purchase a license, and let our AI start trading for you. Sit back and watch the system work intelligently on your behalf.',
        ],
        [
            'q' => 'Can I withdraw my funds anytime?',
            'type' => 'text',
            'text' => 'Yes, users can request withdrawals based on their account balance and platform terms. Processing times may vary depending on the method used.',
        ],
    ];
@endphp

<style>
    .faq-area {
        background: #020617;
        color: #e5e7eb;
    }

    .faq-area .section-title .title,
    .faq-area .section-title .sub-title {
        color: #ffffff;
    }

    .faq-area .accordion-item {
        background: #0f172a;
        border: 1px solid rgba(255, 255, 255, 0.05);
        margin-bottom: 15px;
        border-radius: 10px;
        overflow: hidden;
    }

    .faq-area .accordion-button {
        background: #0f172a;
        color: #ffffff;
        font-weight: 500;
        box-shadow: none;
    }

    .faq-area .accordion-button:not(.collapsed) {
        background: #0c4a3d;
        color: #f1f5f9;
    }

    .faq-area .accordion-button:not(.collapsed):hover {
        background: #0f5647;
        color: #ffffff;
    }

    .faq-area .accordion-button::after {
        filter: invert(1);
    }

    .faq-area .accordion-body {
        background: #020617;
        color: #cbd5f5;
        line-height: 1.6;
    }

    .faq-area .accordion-button.collapsed:hover {
        background: #151d33;
        color: #ffffff;
    }

    .faq-area .accordion-button:focus,
    .faq-area .accordion-button:focus-visible {
        box-shadow: none;
        border-color: transparent;
    }

    .faq-area .accordion-button:focus-visible {
        outline: 2px solid rgba(100, 116, 139, 0.45);
        outline-offset: 2px;
    }

    .faq-area .accordion-button,
    .faq-area .accordion-item {
        transition: background-color 0.25s ease, color 0.25s ease;
    }
</style>

<section class="faq-area pt-130 pb-130" aria-labelledby="invora-faq-section-title">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="section-title text-center mb-50">
                    <span class="sub-title">FAQ</span>
                    <h2 id="invora-faq-section-title" class="title">Frequently Asked <span>Questions</span></h2>
                </div>
            </div>
        </div>

        <div class="faq-wrap">
            <div class="accordion" id="{{ $accordionId }}">
                @foreach ($items as $index => $item)
                    @php
                        $cid = $accordionId.'-c'.$index;
                        $isFirst = $index === 0 && $firstItemOpen;
                    @endphp
                    <div class="accordion-item">
                        <h2 class="accordion-header">
                            <button
                                class="accordion-button @unless ($isFirst) collapsed @endunless"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#{{ $cid }}"
                                aria-expanded="{{ $isFirst ? 'true' : 'false' }}"
                                aria-controls="{{ $cid }}"
                            >
                                {{ $item['q'] }}
                            </button>
                        </h2>
                        <div
                            id="{{ $cid }}"
                            class="accordion-collapse collapse @if ($isFirst) show @endif"
                            data-bs-parent="#{{ $accordionId }}"
                        >
                            <div class="accordion-body">
                                @if (($item['type'] ?? '') === 'risk')
                                    {{ $name }} uses strict risk management systems, including capital allocation controls and automated safeguards, to help protect user funds where the platform can influence process. However, like all trading, returns are not guaranteed. The platform is risk-first: position and margin constraints, exchange rules, liquidation risk, and operational safeguards apply. Funding can change sign or magnitude, and markets can gap—see our
                                    <a href="{{ route('risk-disclosure') }}" class="text-decoration-underline" style="color: #5ee9c9;">risk disclosure</a>
                                    if you want more detail.
                                @else
                                    {{ $item['text'] }}
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </div>
</section>
