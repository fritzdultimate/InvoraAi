<!-- footer-area -->
<footer>
    <div class="footer-area">
        <div class="container">
            <div class="footer-scroll-wrap">
                <button type="button" class="scroll-to-target" data-target="html" aria-label="Back to top"><i class="fas fa-arrow-up"></i></button>
            </div>
            <div class="footer-top">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="footer-widget wow fadeInUp" data-wow-delay=".2s">
                            <a href="{{ route('home-landing') }}" class="f-logo">
                                <img style="width: 60px; height: auto;" src="{{ asset('new_assets/img/logo/invora1.png') }}" alt="{{ config('app.public_name') }}">
                            </a>
                            <div class="footer-content">
                                <p>
                                    {{ config('app.public_name') }} is a market-neutral, AI-driven ecosystem focused on funding yield on perpetual futures—not directional price bets.
                                </p>
                                @include('components.layouts.landing.social-button', ['class' => 'footer-social'])
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-sm-6">
                        <div class="footer-widget wow fadeInUp" data-wow-delay=".35s">
                            <h4 class="fw-title">Explore</h4>
                            <div class="footer-link">
                                <ul>
                                    <li><a href="{{ route('home-landing') }}">Home</a></li>
                                    <li><a href="{{ route('about-us') }}">About us</a></li>
                                    <li><a href="{{ route('contact-us') }}">Contact us</a></li>
                                    <li><a href="{{ route('how-it-works') }}">How it works</a></li>
                                    <li><a href="{{ route('faq') }}">FAQs</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-4 col-sm-6">
                        <div class="footer-widget wow fadeInUp" data-wow-delay=".5s">
                            <h4 class="fw-title">Trading &amp; platform</h4>
                            <div class="footer-link">
                                <ul>
                                    <li><a href="{{ route('trading-bots') }}">Trading bots</a></li>
                                    <li><a href="{{ route('trading-execution') }}">Trading execution</a></li>
                                    <li><a href="{{ route('trading-guidelines') }}">Trading guidelines</a></li>
                                    <li><a href="{{ route('portfolio-management') }}">Portfolio management</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-sm-6">
                        <div class="footer-widget wow fadeInUp" data-wow-delay=".65s">
                            <h4 class="fw-title">Legal &amp; account</h4>
                            <div class="footer-link">
                                <ul>
                                    <li><a href="{{ route('privacy-policy') }}">Privacy policy</a></li>
                                    <li><a href="{{ route('terms') }}">Terms &amp; conditions</a></li>
                                    <li><a href="{{ route('risk-disclosure') }}">Risk disclosure</a></li>
                                    <li><a href="{{ route('login') }}">Login</a></li>
                                    <li><a href="{{ route('register') }}">Register</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-4 col-md-6">
                        <div class="footer-widget wow fadeInUp" data-wow-delay=".8s">
                            <h4 class="fw-title">Resources</h4>
                            <div class="footer-link">
                                <ul>
                                    <li>
                                        <a href="{{ asset('new_assets/docs/INVORA DOCUMENTATION .pdf') }}" target="_blank" rel="noopener">Invora AI overview</a>
                                    </li>
                                </ul>
                            </div>
                            <h4 class="fw-title mt-4">Stay updated</h4>
                            <div class="footer-newsletter">
                                <p class="mb-3" style="font-size: 0.9rem;">
                                    For media, partnerships, or product updates, reach out—we will point you to the right channel.
                                </p>
                                <a href="{{ route('contact-us') }}" class="btn" style="padding: 12px 20px; font-size: 14px;">Contact us</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="copyright-text">
                            <p>Copyright &copy; {{ date('Y') }} {{ config('app.public_name') }}. All rights reserved.</p>
                        </div>
                    </div>
                    <div class="col-lg-6 d-none d-sm-block">
                        <div class="footer-menu">
                            <ul>
                                <li><a href="{{ route('terms') }}">Terms &amp; conditions</a></li>
                                <li><a href="{{ route('privacy-policy') }}">Privacy policy</a></li>
                                <li><a href="{{ route('risk-disclosure') }}">Risk disclosure</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer-area-end -->
