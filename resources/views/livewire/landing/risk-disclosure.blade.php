<div class="invora-legal-page-root">
    @include('components.landing.legal-page-styles')

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
                        <p class="legal-kicker mb-2" style="color: #00b08b; letter-spacing: 0.12em;">Legal</p>
                        <h2 class="title" style="font-size: clamp(1.75rem, 4vw, 2.35rem);">
                            Risk <span style="color: #009A76;">Disclosure</span>
                        </h2>
                        <p class="mt-3 mb-0 text-light" style="max-width: 640px; margin-left: auto; margin-right: auto;">
                            Understand the risks before you allocate capital or use {{ config('app.public_name') }}.
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
                            <p class="legal-kicker">Risk Disclosure</p>
                            <h1 class="legal-title">Important Information About Material Risks</h1>
                            <p class="legal-lead mb-0">
                                Using the platform involves risks, and it is essential that all users understand the potential
                                for loss before participating in any financial activities through {{ config('app.public_name') }}.
                                This Risk Disclosure Statement is provided to ensure transparency and awareness.
                            </p>

                            <h2>1. Market Risks</h2>
                            <p>
                                {{ config('app.public_name') }} is designed around market-neutral and structural strategies that do not depend on correctly predicting whether crypto prices will rise or fall. However, you remain exposed to broader cryptocurrency market risks, including extreme volatility, liquidity stress, exchange or custody issues, regulatory changes, and macro events that can affect funding dynamics, execution, or platform availability. Past performance is not indicative of future results, and there is no guarantee that you will achieve profits or avoid losses.
                            </p>

                            <h2>2. Strategy &amp; Market Risks</h2>
                            <p>
                                Participation in cryptocurrency markets and related strategies carries inherent risk, even when approaches are described as market-neutral or systematic. Risks include, without limitation: sudden changes in funding rates, basis relationships, spreads, margin requirements, or liquidity; execution slippage; model, configuration, or operational error; counterparty and exchange risk; and broader factors such as interest-rate shifts, geopolitical events, or regulatory action that can affect crypto markets and infrastructure. These risks exist independently of whether spot prices trend up or down.
                            </p>

                            <h2>3. Loss of Capital</h2>
                            <p>
                                You may lose some or all of the capital you allocate. {{ config('app.public_name') }} does not guarantee the preservation of capital, any particular return, or that strategies will perform as illustrated under stress. Outcomes can be affected by fees, funding costs, leverage (if used), forced liquidations, halts, or other market and operational events.
                            </p>

                            <h2>4. No Financial Advice</h2>
                            <p>
                                Content on or through {{ config('app.public_name') }} is for general educational and informational purposes only. It is not personalized financial, legal, tax, or trading advice and does not consider your individual circumstances. Automated or systematic features do not replace professional judgment. Seek advice from qualified professionals before making financial decisions.
                            </p>

                            <h2>5. Technology Risks</h2>
                            <p>
                                {{ config('app.public_name') }} relies on software, networks, APIs, exchanges, wallets, and third-party infrastructure. Outages, latency, bugs, connectivity loss, blockchain congestion, or cyber incidents may delay or prevent trading, reporting, deposits, withdrawals, or access to the platform. You are responsible for safeguarding credentials, devices, and any recovery codes. Despite security measures, no system is immune to attack or failure.
                            </p>

                            <h2>6. Regulatory Risks</h2>
                            <p>
                                Digital assets and derivatives are subject to evolving rules in different jurisdictions. Laws may change in ways that affect availability of products, taxation, reporting, or your ability to use the platform. You are solely responsible for determining whether your use of {{ config('app.public_name') }} is lawful where you live and for complying with applicable regulations.
                            </p>

                            <h2>7. Third-Party Risks</h2>
                            <p>
                                {{ config('app.public_name') }} may rely on third parties (for example payment processors, analytics, custody, banking rails, or liquidity venues). Their policies, solvency, security, or downtime can affect you. We do not control third parties and cannot guarantee their performance or continuity of service.
                            </p>

                            <h2>8. Suitability</h2>
                            <p>
                                Crypto and derivatives-style exposure may not be appropriate for everyone. Consider your financial position, time horizon, liquidity needs, and ability to bear loss before participating. If you do not fully understand how strategies work—including funding, margin, and non-directional risk—you should not use the platform.
                            </p>

                            <h2>9. Limitation of Liability</h2>
                            <p>
                                To the fullest extent permitted by law, {{ config('app.public_name') }} and its affiliates disclaim liability for direct, indirect, incidental, special, consequential, or punitive damages arising from your use of the platform, including trading losses, missed opportunities, or data loss. Your use is at your own risk; you accept responsibility for decisions you make and capital you deploy.
                            </p>

                            <h2>10. Acknowledgment</h2>
                            <p>
                                By accessing or using {{ config('app.public_name') }}, you confirm that you have read and understood this Risk Disclosure Statement, that you accept the risks described (including those not exhaustively listed), and that you proceed voluntarily.
                            </p>

                            <p class="mb-0">
                                For questions or clarifications regarding these risks, please contact us at
                                <a href="mailto:{{ env('SUPPORT_EMAIL', 'support@invora.ai') }}">{{ env('SUPPORT_EMAIL', 'support@invora.ai') }}</a>.
                                Understanding these risks is essential for responsible participation in financial
                                activities.
                            </p>

                            <div class="invora-legal-related">
                                Related:
                                <a href="{{ route('privacy-policy') }}">Privacy policy</a>
                                <span class="mx-2">·</span>
                                <a href="{{ route('terms') }}">Terms &amp; conditions</a>
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
