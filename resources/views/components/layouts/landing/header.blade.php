<!-- header-area -->
<header id="header">
    <div id="header-fixed-height"></div>
    <div id="sticky-header" class="menu-area">
        <div class="container custom-container">
            <div class="row">
                <div class="col-12">
                    <div class="mobile-nav-toggler"><i class="fas fa-bars"></i></div>
                    <div class="menu-wrap">
                        <nav class="menu-nav">
                            <div class="logo">
                                <a href="{{ route('home-landing') }}">
                                    <img style="width: 60px; height: auto" src="assets/img/logo/invora1.png" alt="">
                                </a>
                            </div>
                            <div class="navbar-wrap main-menu d-none d-lg-flex">
                                <ul class="navigation">
                                    <li><a href="{{ route('home-landing') }}" class="section-link">Home</a></li>
                                    <li><a href="#about" class="section-link">About us</a></li>
                                    <li><a href="#sales" class="section-link">Sales</a></li>
                                    <li><a href="#roadmap" class="section-link">Roadmap</a></li>
                                    <li><a href="#contact" class="section-link">Contact us</a></li>
                                </ul>
                            </div>
                            <div class="header-action d-none d-md-block">
                                <ul>
                                    <li class="header-lang"><span class="selected-lang">ENG</span>
                                        <ul class="lang-list">
                                            <li><a href="#">IND</a></li>
                                            <li><a href="#">BNG</a></li>
                                            <li><a href="#">TUR</a></li>
                                            <li><a href="#">CIN</a></li>
                                        </ul>
                                    </li>
                                    <li class="header-btn"><a href="{{ route('login') }}" class="btn">Login</a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <!-- Mobile Menu  -->
                    <div class="mobile-menu">
                        <nav class="menu-box">
                            <div class="close-btn"><i class="fas fa-times"></i></div>
                            <div class="nav-logo">
                                <a href="{{ route('home-landing') }}">
                                    <img style="width: 60px; height: auto" src="assets/img/logo/invora1.png" alt="{{ env('APP_NAME') }}" title="{{ env('APP_NAME') }}">
                                </a>
                            </div>
                            <div class="menu-outer">
                                <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
                            </div>
                            <div class="social-links">
                                <ul class="clearfix">
                                    <li><a href="#"><i class="fab fa-facebook-f"></i></a></li>
                                    <li><a href="#"><i class="fab fa-twitter"></i></a></li>
                                    <li><a href="#"><i class="fab fa-instagram"></i></a></li>
                                    <li><a href="#"><i class="fab fa-linkedin-in"></i></a></li>
                                    <li><a href="#"><i class="fab fa-youtube"></i></a></li>
                                </ul>
                            </div>
                        </nav>
                    </div>
                    <div class="menu-backdrop"></div>
                    <!-- End Mobile Menu -->
                </div>
            </div>
        </div>
    </div>
</header>
<!-- header-area-end -->