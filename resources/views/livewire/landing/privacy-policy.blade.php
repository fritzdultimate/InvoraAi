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
                        Privacy <span style="color: #009A76;">Policy</span>
                    </h2>
                    <p class="mt-3 mb-0 text-light" style="max-width: 640px; margin-left: auto; margin-right: auto;">
                        How we collect, use, and protect your information at {{ config('app.public_name') }}.
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
                        <p class="legal-kicker">Privacy Policy</p>
                        <h1 class="legal-title">Your Privacy Matters to Us</h1>
                        <p class="legal-lead mb-0">
                            At {{ config('app.public_name') }}, we value your trust. This Privacy Policy details how we collect, use, share, and protect the information you provide while using our services. By using {{ config('app.public_name') }}, you agree to the terms outlined below.
                        </p>

                        <h2>1. Information We Collect</h2>
                        <p>
                            We collect both personal and non-personal information to provide the best service possible. Personal information may include your name, email address, phone number, payment details, and any other information you provide voluntarily. Non-personal information includes browser type, IP address, device information, and interactions with our website or app.
                        </p>

                        <h2>2. How We Use Your Information</h2>
                        <p>
                            Your data is used to improve our services, process transactions, respond to inquiries, and deliver personalized experiences. We may also use information for analytics, internal research, marketing communications (if consented), and compliance with legal obligations.
                        </p>

                        <h2>3. Sharing Your Information</h2>
                        <p>
                            We do not sell your personal data. However, we may share information with trusted third-party service providers for operational purposes, such as payment processing, analytics, or customer support. These providers are contractually required to protect your data.
                        </p>

                        <h2>4. Data Security Measures</h2>
                        <p>
                            {{ config('app.public_name') }} implements robust security protocols to safeguard your information. This includes encryption, secure servers, access controls, and regular security audits to prevent unauthorized access, disclosure, or modification of your data.
                        </p>

                        <h2>5. Cookies and Tracking</h2>
                        <p>
                            We use cookies and similar technologies to enhance user experience, track website usage, and provide relevant content. You may disable cookies through your browser settings, although some features may not function properly without them.
                        </p>

                        <h2>6. Your Rights</h2>
                        <p>
                            You have the right to access, update, or delete your personal information at any time. You may also object to certain processing activities, including marketing communications. Requests can be made via
                            <a href="mailto:{{ env('PRIVACY_EMAIL', 'support@invora.ai') }}">{{ env('PRIVACY_EMAIL', 'support@invora.ai') }}</a>.
                        </p>

                        <h2>7. Children’s Privacy</h2>
                        <p>
                            Our services are not directed to children under 13 years old, and we do not knowingly collect personal data from minors. If we become aware of any data from children, we will promptly delete it.
                        </p>

                        <h2>8. International Users</h2>
                        <p>
                            For users accessing {{ config('app.public_name') }} from outside our primary jurisdiction, your data may be processed according to local data protection laws. We ensure that international data transfers are secure and compliant with applicable regulations.
                        </p>

                        <h2>9. Retention of Data</h2>
                        <p>
                            We retain personal information only as long as necessary for operational, legal, or regulatory purposes. When data is no longer required, we ensure secure deletion or anonymization.
                        </p>

                        <h2>10. Changes to This Privacy Policy</h2>
                        <p>
                            We may update this Privacy Policy from time to time to reflect operational changes, regulatory updates, or new services. Updated versions will be published on this page, and we encourage users to review periodically.
                        </p>

                        <p class="mb-0">
                            For questions regarding this Privacy Policy or your personal data, contact us at
                            <a href="mailto:{{ env('PRIVACY_EMAIL', 'support@invora.ai') }}">{{ env('PRIVACY_EMAIL', 'support@invora.ai') }}</a>.
                            Your privacy and trust are our top priorities.
                        </p>

                        <div class="invora-legal-related">
                            Related:
                            <a href="{{ route('terms') }}">Terms and Conditions</a>
                            <span class="mx-2">·</span>
                            <a href="{{ route('risk-disclosure') }}">Risk Disclosure</a>
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

