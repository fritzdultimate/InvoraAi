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
                                    <img style="width: 60px; height: auto"
                                        src="{{ asset('new_assets/img/logo/invora1.png') }}" alt="">
                                </a>
                            </div>
                            <div
                                class="navbar-wrap main-menu d-none d-lg-flex flex-column flex-lg-row align-items-lg-center">
                                <ul class="navigation">
                                    <li><a href="{{ route('home-landing') }}" class="section-link">Home</a></li>
                                    <li><a href="{{ route('about-us') }}" class="section-link">About us</a></li>
                                    <li><a href="{{ route('contact-us') }}" class="section-link">Contact us</a></li>
                                    <li class="menu-item-has-children">
                                        <a href="javascript:void(0)" class="section-link">Others</a>
                                        <ul class="sub-menu">
                                            <li><a href="{{ route('faq') }}">FAQs</a></li>
                                            <li><a href="{{ route('how-it-works') }}">How it works</a></li>
                                            <li><a href="{{ route('trading-bots') }}">Trading bots</a></li>
                                            <li><a href="{{ route('trading-execution') }}">Trading Execution</a></li>
                                            <li><a href="{{ route('trading-guidelines') }}">Trading Guidelines</a></li>
                                            <li><a href="{{ route('portfolio-management') }}">Portfolio Management</a>
                                            </li>
                                            <li><a href="{{ route('privacy-policy') }}">Privacy policy</a></li>
                                            <li><a href="{{ route('terms') }}">Terms &amp; conditions</a></li>
                                            <li><a href="{{ route('risk-disclosure') }}">Risk disclosure</a></li>
                                        </ul>
                                    </li>
                                </ul>
                                {{-- Cloned into the hamburger panel; hidden on desktop where header-action shows Login
                                --}}
                                <div class="invora-mobile-menu-auth d-lg-none w-100 px-1 pt-3 mt-2"
                                    style="border-top: 1px solid rgba(255,255,255,0.12);">
                                    <a href="{{ route('login') }}" class="btn w-100 mb-2 d-block text-center">Login</a>
                                    <a href="{{ route('register') }}" class="btn w-100 d-block text-center"
                                        style="background: transparent; border: 2px solid rgba(0, 176, 139, 0.65); color: #5ee9c9;">Sign
                                        up</a>
                                </div>
                            </div>
                            <div class="header-action d-none d-md-block">
                                <ul>
                                    <!-- <li class="header-lang"><span class="selected-lang">ENG</span>
                                        <ul class="lang-list">
                                            <li><a href="#">IND</a></li>
                                            <li><a href="#">BNG</a></li>
                                            <li><a href="#">TUR</a></li>
                                            <li><a href="#">CIN</a></li>
                                        </ul>
                                    </li> -->
                                    <li class="header-lang" id="invorLangSwitcher" style="position:relative;">
                                        <div class="invora-lang-trigger" id="invorLangTrigger" style="display: none">
                                            <span class="invora-lang-flag" id="invorLangFlag">🇬🇧</span>
                                            <span class="invora-lang-code" id="invorLangCode">ENG</span>
                                            <svg class="invora-lang-chevron" viewBox="0 0 16 16" fill="none">
                                                <path d="M4 6l4 4 4-4" stroke="rgba(255,255,255,0.6)" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class="invora-lang-dropdown" id="invorLangDropdown" style="display: none">
                                            <div class="invora-lang-dropdown-header">Select Language</div>
                                            <div class="invora-lang-option active" data-flag="🇬🇧" data-code="ENG"
                                                data-gt="en">
                                                <span class="invora-lang-emoji">🇬🇧</span>
                                                <div>
                                                    <div class="invora-lang-name">English</div>
                                                    <div class="invora-lang-native">English</div>
                                                </div>
                                                <svg class="invora-lang-check" viewBox="0 0 16 16" fill="none">
                                                    <path d="M3 8l3.5 3.5L13 4.5" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <div class="invora-lang-option" data-flag="🇩🇪" data-code="DEU"
                                                data-gt="de">
                                                <span class="invora-lang-emoji">🇩🇪</span>
                                                <div>
                                                    <div class="invora-lang-name">German</div>
                                                    <div class="invora-lang-native">Deutsch</div>
                                                </div>
                                                <svg class="invora-lang-check" viewBox="0 0 16 16" fill="none">
                                                    <path d="M3 8l3.5 3.5L13 4.5" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <div class="invora-lang-option" data-flag="🇷🇺" data-code="RUS"
                                                data-gt="ru">
                                                <span class="invora-lang-emoji">🇷🇺</span>
                                                <div>
                                                    <div class="invora-lang-name">Russian</div>
                                                    <div class="invora-lang-native">Русский</div>
                                                </div>
                                                <svg class="invora-lang-check" viewBox="0 0 16 16" fill="none">
                                                    <path d="M3 8l3.5 3.5L13 4.5" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <div class="invora-lang-divider"></div>
                                            <div class="invora-lang-option" data-flag="🇬🇷" data-code="ELL"
                                                data-gt="el">
                                                <span class="invora-lang-emoji">🇬🇷</span>
                                                <div>
                                                    <div class="invora-lang-name">Greek</div>
                                                    <div class="invora-lang-native">Ελληνικά</div>
                                                </div>
                                                <svg class="invora-lang-check" viewBox="0 0 16 16" fill="none">
                                                    <path d="M3 8l3.5 3.5L13 4.5" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <!-- Hidden Google Translate element -->
                                        <div id="google_translate_element"></div>
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
                                    <img style="width: 60px; height: auto"
                                        src="{{ asset('new_assets/img/logo/invora1.png') }}"
                                        alt="{{ config('app.public_name') }}" title="{{ config('app.public_name') }}">
                                </a>
                            </div>
                            <div class="menu-outer">
                                <!--Here Menu Will Come Automatically Via Javascript / Same Menu as in Header-->
                            </div>
                            <div style="padding: 0px 10px; margin-top: 20px;">
                                <ul>
                                    <li class="header-lang" id="invorLangSwitcherMobile" style="position:relative;">
                                        <div class="invora-lang-trigger" id="invorLangTriggerMobile" style="display: none">
                                            <span class="invora-lang-flag" id="invorLangFlag">🇬🇧</span>
                                            <span class="invora-lang-code" id="invorLangCode">ENG</span>
                                            <svg class="invora-lang-chevron" viewBox="0 0 16 16" fill="none">
                                                <path d="M4 6l4 4 4-4" stroke="rgba(255,255,255,0.6)" stroke-width="1.5"
                                                    stroke-linecap="round" stroke-linejoin="round" />
                                            </svg>
                                        </div>
                                        <div class="invora-lang-dropdown" id="invorLangDropdownMobile" style="display: none">
                                            <div class="invora-lang-dropdown-header">Select Language</div>
                                            <div class="invora-lang-option active" data-flag="🇬🇧" data-code="ENG"
                                                data-gt="en">
                                                <span class="invora-lang-emoji">🇬🇧</span>
                                                <div>
                                                    <div class="invora-lang-name">English</div>
                                                    <div class="invora-lang-native">English</div>
                                                </div>
                                                <svg class="invora-lang-check" viewBox="0 0 16 16" fill="none">
                                                    <path d="M3 8l3.5 3.5L13 4.5" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <div class="invora-lang-option" data-flag="🇩🇪" data-code="DEU"
                                                data-gt="de">
                                                <span class="invora-lang-emoji">🇩🇪</span>
                                                <div>
                                                    <div class="invora-lang-name">German</div>
                                                    <div class="invora-lang-native">Deutsch</div>
                                                </div>
                                                <svg class="invora-lang-check" viewBox="0 0 16 16" fill="none">
                                                    <path d="M3 8l3.5 3.5L13 4.5" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <div class="invora-lang-option" data-flag="🇷🇺" data-code="RUS"
                                                data-gt="ru">
                                                <span class="invora-lang-emoji">🇷🇺</span>
                                                <div>
                                                    <div class="invora-lang-name">Russian</div>
                                                    <div class="invora-lang-native">Русский</div>
                                                </div>
                                                <svg class="invora-lang-check" viewBox="0 0 16 16" fill="none">
                                                    <path d="M3 8l3.5 3.5L13 4.5" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                            <div class="invora-lang-divider"></div>
                                            <div class="invora-lang-option" data-flag="🇬🇷" data-code="ELL"
                                                data-gt="el">
                                                <span class="invora-lang-emoji">🇬🇷</span>
                                                <div>
                                                    <div class="invora-lang-name">Greek</div>
                                                    <div class="invora-lang-native">Ελληνικά</div>
                                                </div>
                                                <svg class="invora-lang-check" viewBox="0 0 16 16" fill="none">
                                                    <path d="M3 8l3.5 3.5L13 4.5" stroke="currentColor" stroke-width="2"
                                                        stroke-linecap="round" stroke-linejoin="round" />
                                                </svg>
                                            </div>
                                        </div>
                                        <!-- Hidden Google Translate element -->
                                        <div id="google_translate_element_mobile"></div>
                                    </li>
                                </ul>
                            </div>
                            <div class="social-links">
                                @include('components.layouts.landing.social-button', ['class' => 'clearfix'])
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