<!-- footer-area -->
<footer>
    <div class="footer-area">
        <div class="container">
            <div class="footer-scroll-wrap">
                <button class="scroll-to-target" data-target="html"><i class="fas fa-arrow-up"></i></button>
            </div>
            <div class="footer-top">
                <div class="row">
                    <div class="col-xl-3 col-lg-4 col-md-6">
                        <div class="footer-widget wow fadeInUp" data-wow-delay=".2s">
                            <a href="{{ route('home-landing') }}" class="f-logo">
                                <img style="width: 60px; height: auto;" src="{{ asset('new_assets/img/logo/invora1.png') }}" alt="">
                            </a>
                            <div class="footer-content">
                                <p>
                                    InvoraAI is an advanced AI-driven investment platform designed to deliver
                                    intelligent, data-driven strategies with a focus on risk management and
                                    long-term performance.
                                </p>
                                <ul class="footer-social">
                                    <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-skype"></i></a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-3 col-lg-5 col-sm-6">
                        <div class="footer-widget  wow fadeInUp" data-wow-delay=".4s">
                            <h4 class="fw-title">Platform</h4>
                            <div class="footer-link">
                                <ul>
                                    <li><a href="#">How it Works</a></li>
                                    <li><a href="#">AI Strategy</a></li>
                                    <li><a href="#">Contact Us</a></li>
                                    <li><a href="#">Terms & Services</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-2 col-lg-3 col-sm-6">
                        <div class="footer-widget wow fadeInUp" data-wow-delay=".6s">
                            <h4 class="fw-title">Support</h4>
                            <div class="footer-link">
                                <ul>
                                    <li><a href="#">Help Center</a></li>
                                    <li><a href="#">FAQs</a></li>
                                    <li><a href="#">Privacy Policy</a></li>
                                    <li><a href="#">Terms & Conditions</a></li>
                                    <li><a href="#">Security</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-4 col-lg-4 col-md-6">
                        <div class="footer-widget wow fadeInUp" data-wow-delay=".8s">
                            <h4 class="fw-title">Stay Updated</h4>
                            <div class="footer-newsletter">
                                <p>
                                    Get the latest updates on AI strategies, platform improvements,
                                    and investment insights directly to your inbox.
                                </p>
                                <form action="#">
                                    <input type="email" placeholder="Info@gmail.com" required>
                                    <button type="submit"><i class="fas fa-paper-plane"></i></button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-lg-6">
                        <div class="copyright-text">
                            <p>Copyright &copy; {{ date('Y') }} {{ config('app.public_name') }}. All Rights Reserved</p>
                        </div>
                    </div>
                    <div class="col-lg-6 d-none d-sm-block">
                        <div class="footer-menu">
                            <ul>
                                <li><a href="#">Terms and conditions</a></li>
                                <li><a href="#">Privacy policy</a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<!-- footer-area-end -->