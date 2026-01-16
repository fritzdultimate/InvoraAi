@props([
    'title' => 'Weclome to ' . env('APP_NAME'),
    'desc' => 'AI-Powered Crypto Arbitrage Investing',
    'subtitle' => env('APP_NAME') .' uses advanced AI systems to identify real-time cryptocurrency price differences across multiple markets. We buy assets at lower prices and resell at higher rates, helping you earn from market inefficiencies automatically, transparently, and efficiently.',
    'showCta' => true
])
<div 
    class="elementor-element elementor-element-359f100 e-flex e-con-boxed e-con e-parent" 
    data-id="359f100"
    data-element_type="container" 
    data-settings="{&quot;background_background&quot;:&quot;classic&quot;}"
>
    <div class="e-con-inner">
        <div class="elementor-element elementor-element-8f4954a e-flex e-con-boxed e-con e-child" data-id="8f4954a"
            data-element_type="container">
            <div class="e-con-inner">
                <div class="elementor-element elementor-element-a62daaf elementor-invisible elementor-widget elementor-widget-heading"
                    data-id="a62daaf" data-element_type="widget"
                    data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:200}"
                    data-widget_type="heading.default">
                    <div class="elementor-widget-container">
                        <h6 class="elementor-heading-title elementor-size-default">{{ $title }}</h6>
                    </div>
                </div>
                <div class="elementor-element elementor-element-48a99d2 elementor-invisible elementor-widget elementor-widget-heading"
                    data-id="48a99d2" data-element_type="widget"
                    data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300}"
                    data-widget_type="heading.default">
                    <div class="elementor-widget-container">
                        <h1 class="elementor-heading-title elementor-size-default">
                            {{ $desc }}

                        </h1>
                    </div>
                </div>
                <div class="elementor-element elementor-element-192a817 elementor-invisible elementor-widget elementor-widget-heading"
                    data-id="192a817" data-element_type="widget"
                    data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:350}"
                    data-widget_type="heading.default">
                    <div class="elementor-widget-container">
                        <div class="elementor-heading-title elementor-size-default">
                            {{ $subtitle }}
                        </div>
                    </div>
                </div>
                @if ($showCta)
                    <div class="elementor-element elementor-element-14e873f e-con-full e-flex elementor-invisible e-con e-child"
                        data-id="14e873f" data-element_type="container"
                        data-settings="{&quot;animation&quot;:&quot;fadeInUp&quot;,&quot;animation_dela
                             y          &quot;:375}">
                        <div class="elementor-element elementor-element-cb27b2a elementor-widget elementor-widget-button"
                            data-id="cb27b2a" data-element_type="widget" data-widget_type="button.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-button-wrapper">
                                    <a class="elementor-button elementor-button-link elementor-size-sm" href="{{ route('register') }}">
                                        <span class="elementor-button-content-wrapper">
                                            <span class="elementor-button-icon">
                                                <svg aria-hidden="true" class="e-font-icon-svg e-fas-arrow-right"
                                                    viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                                                    <path
                                                        d="M190.5 66.9l22.2-22.2c9.4-9.4 24.6-9.4 33.9 0L441 239c9.4 9.4 9.4 24.6 0 33.9L246.6 467.3c-9.4 9.4-24.6 9.4-33.9 0l-22.2-22.2c-9.5-9.5-9.3-25 .4-34.3L311.4 296H24c-13.3 0-24-10.7-24-24v-32c0-13.3 10.7-24 24-24h287.4L190.9 101.2c-9.8-9.3-10-24.8-.4-34.3z">
                                                    </path>
                                                </svg> </span>
                                            <span class="elementor-button-text">Get Started</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>

                                                           <div class="elementor-element elementor-element-fb053c4 elementor-widget elementor-widget-button"
                            data-id="fb053c4" data-element_type="widget" data-widget_type="button.default">
                            <div class="elementor-widget-container">
                                <div class="elementor-button-wrapper">
                                    <a class="elementor-button elementor-button-link elementor-size-sm" href="{{ route('login') }}">
                                        <span class="elementor-button-content-wrapper">
                                            <span class="elementor-button-text">Learn more</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>

                @endif
            </div>
        </div>
    </div>
</div>