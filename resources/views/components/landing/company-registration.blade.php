<!-- company-registration — incorporation proof, split: content left, certificate right -->
<section id="company-registration" class="about-area company-registration-area" style="padding-top: 40px; padding-bottom: 40px;">
    <div class="container">
        <div class="row align-items-center gy-4">
            <div class="col-lg-6 order-lg-1 order-2">
                <div class="about-content wow fadeInLeft" data-wow-delay=".2s">
                    <div class="section-title mb-30">
                        <span class="sub-title">Corporate Registration</span>
                        <h2 class="title">Registered &amp; <span>Incorporated</span></h2>
                    </div>
                    <p>
                        {{ config('app.public_name') }} is operated by <strong>Pharmalectin, Inc.</strong>, a company
                        duly incorporated in the British Virgin Islands under the BVI Business Companies Act, 2004.
                    </p>
                    <ul class="list-unstyled company-registration-facts" style="line-height: 2;">
                        <li><strong>Legal name:</strong> Pharmalectin, Inc.</li>
                        <li><strong>BVI company number:</strong> 2057427</li>
                        <li><strong>Date of incorporation:</strong> 17 March 2021</li>
                        <li><strong>Jurisdiction:</strong> British Virgin Islands</li>
                    </ul>
                    <a href="{{ asset('assets/images/about/certificate-of-incorporation.png') }}" class="btn" target="_blank" rel="noopener">
                        View Certificate of Incorporation
                    </a>
                </div>
            </div>
            <div class="col-lg-6 order-lg-2 order-1">
                <div class="about-img text-center wow fadeInRight" data-wow-delay=".2s">
                    <a href="{{ asset('assets/images/about/certificate-of-incorporation.png') }}" target="_blank" rel="noopener">
                        <img
                            src="{{ asset('assets/images/about/certificate-of-incorporation.png') }}"
                            alt="Certificate of Incorporation — Pharmalectin, Inc., BVI Company No. 2057427"
                            loading="lazy"
                            style="max-width: 100%; height: auto; border: 1px solid rgba(255,255,255,0.15); border-radius: 6px; box-shadow: 0 10px 30px rgba(0,0,0,0.25);"
                        >
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>
<!-- company-registration-end -->
