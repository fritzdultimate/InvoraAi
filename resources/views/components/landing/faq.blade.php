<!-- faq-area -->
<style>
    /* FAQ DARK THEME FIX */

    .faq-area {
        background: #020617;
        color: #e5e7eb;
    }

    .faq-area .section-title .title,
    .faq-area .section-title .sub-title {
        color: #ffffff;
    }

    /* Accordion Container */
    .faq-area .accordion-item {
        background: #0f172a;
        border: 1px solid rgba(255, 255, 255, 0.05);
        margin-bottom: 15px;
        border-radius: 10px;
        overflow: hidden;
    }

    /* Question Button */
    .faq-area .accordion-button {
        background: #0f172a;
        color: #ffffff;
        font-weight: 500;
        box-shadow: none;
    }

    /* Active/Open */
    .faq-area .accordion-button:not(.collapsed) {
        background: #009A76;
        color: #ffffff;
    }

    /* Arrow icon fix */
    .faq-area .accordion-button::after {
        filter: invert(1);
    }

    /* Answer Body */
    .faq-area .accordion-body {
        background: #020617;
        color: #cbd5f5;
        line-height: 1.6;
    }

    /* Hover effect */
    .faq-area .accordion-button:hover {
        background: #111c3a;
        color: #ffffff;
    }

    /* Smooth transition */
    .faq-area .accordion-button,
    .faq-area .accordion-item {
        transition: all 0.3s ease;
    }
</style>
<section class="faq-area pt-130 pb-130">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-xl-6">
                <div class="section-title text-center mb-50">
                    <span class="sub-title">FAQ</span>
                    <h2 class="title">Frequently Asked <span>Questions</span></h2>
                </div>
            </div>
        </div>

        <div class="faq-wrap">
            <div class="accordion" id="accordionExample">

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button" type="button" data-bs-toggle="collapse" data-bs-target="#faq1">
                            What is InvoraAI?
                        </button>
                    </h2>
                    <div id="faq1" class="accordion-collapse collapse show" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            InvoraAI is an AI-driven investment platform that uses advanced algorithms to analyze
                            financial markets,
                            manage risk, and execute trading strategies automatically.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq2">
                            How does the AI generate returns?
                        </button>
                    </h2>
                    <div id="faq2" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            The system monitors market data in real-time and uses market-neutral strategies, combining
                            long and short positions,
                            to take advantage of price inefficiencies while reducing exposure to market direction.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq3">
                            Is my investment safe?
                        </button>
                    </h2>
                    <div id="faq3" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            InvoraAI uses strict risk management systems, including capital allocation controls and
                            automated safeguards,
                            to help protect user funds. However, like all investments, returns are not guaranteed.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq4">
                            Do I need trading experience to use InvoraAI?
                        </button>
                    </h2>
                    <div id="faq4" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            No. The platform is designed for both beginners and experienced investors. The AI handles
                            complex trading decisions
                            while you monitor your investment performance.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq5">
                            How can I start investing?
                        </button>
                    </h2>
                    <div id="faq5" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            Simply create an account, choose a suitable investment plan, and fund your account to begin.
                            The system will handle the rest.
                        </div>
                    </div>
                </div>

                <div class="accordion-item">
                    <h2 class="accordion-header">
                        <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                            data-bs-target="#faq6">
                            Can I withdraw my funds anytime?
                        </button>
                    </h2>
                    <div id="faq6" class="accordion-collapse collapse" data-bs-parent="#accordionExample">
                        <div class="accordion-body">
                            Yes, users can request withdrawals based on their account balance and platform terms.
                            Processing times may vary depending on the method used.
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</section>
<!-- faq-area-end -->