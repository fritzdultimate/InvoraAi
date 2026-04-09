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
                            Terms <span style="color: #009A76;">&amp; Conditions</span>
                        </h2>
                        <p class="mt-3 mb-0 text-light" style="max-width: 640px; margin-left: auto; margin-right: auto;">
                            Rules governing your use of {{ config('app.public_name') }} services.
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
                            <p class="legal-kicker">Terms and Conditions</p>
                            <h1 class="legal-title">Please Read These Terms Carefully</h1>
                            <p class="legal-lead mb-0">
                                Welcome to {{ config('app.public_name') }}. By accessing or using our services, you agree to comply with and be bound by the following Terms and Conditions. Please read them carefully before using our website, platform, or services.
                            </p>

                            <h2>1. Acceptance of Terms</h2>
                            <p>
                                By using {{ config('app.public_name') }}, you acknowledge that you have read, understood, and agree to be bound by these Terms. If you do not agree, please refrain from using our services.
                            </p>

                            <h2>2. Eligibility</h2>
                            <p>
                                You must be at least 18 years old or of legal age in your jurisdiction to use {{ config('app.public_name') }}. By using our services, you represent and warrant that you meet these requirements.
                            </p>

                            <h2>3. Account Responsibilities</h2>
                            <p>
                                Users are responsible for maintaining the confidentiality of their account information, including login credentials. You agree to notify us immediately of any unauthorized use of your account. All activities under your account are your responsibility.
                            </p>

                            <h2>4. Use of Services</h2>
                            <p>
                                You agree to use {{ config('app.public_name') }} only for lawful purposes. Prohibited activities include, but are not limited to, fraudulent activity, illegal transactions, infringement of intellectual property rights, or violating any applicable laws.
                            </p>

                            <h2>5. Payments and Fees</h2>
                            <p>
                                Any payments, subscription fees, or charges incurred while using our platform must be made promptly and accurately. Failure to pay may result in suspension or termination of your account.
                            </p>

                            <h2>6. Intellectual Property</h2>
                            <p>
                                All content, logos, trademarks, and software associated with {{ config('app.public_name') }} are the property of {{ config('app.public_name') }} or its licensors. You may not reproduce, distribute, or use any materials without explicit permission.
                            </p>

                            <h2>7. Disclaimers</h2>
                            <p>
                                {{ config('app.public_name') }} provides its services &quot;as is&quot; and makes no warranties regarding accuracy, reliability, or suitability. Use of the platform is at your own risk. We are not liable for any financial losses, damages, or interruptions in service.
                            </p>

                            <h2>8. Limitation of Liability</h2>
                            <p>
                                To the maximum extent permitted by law, {{ config('app.public_name') }} shall not be liable for any indirect, incidental, special, or consequential damages arising from your use of the platform.
                            </p>

                            <h2>9. Termination</h2>
                            <p>
                                We reserve the right to suspend or terminate your account at our discretion, particularly in cases of breach of these Terms or illegal activity.
                            </p>

                            <h2>10. Governing Law</h2>
                            <p>
                                These Terms shall be governed by and construed in accordance with the laws of the jurisdiction in which {{ config('app.public_name') }} operates. Any disputes will be subject to the exclusive jurisdiction of the competent courts.
                            </p>

                            <h2>11. Changes to Terms</h2>
                            <p>
                                {{ config('app.public_name') }} may update these Terms and Conditions from time to time. Updated versions will be published on this page. Users are encouraged to review them periodically.
                            </p>

                            <p class="mb-0">
                                For questions or clarifications regarding these Terms and Conditions, please contact us at
                                <a href="mailto:{{ env('SUPPORT_EMAIL', 'support@invora.ai') }}">{{ env('SUPPORT_EMAIL', 'support@invora.ai') }}</a>.
                                Your compliance and understanding of these Terms ensure a safe and reliable platform for everyone.
                            </p>

                            <div class="invora-legal-related">
                                Related:
                                <a href="{{ route('privacy-policy') }}">Privacy policy</a>
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
