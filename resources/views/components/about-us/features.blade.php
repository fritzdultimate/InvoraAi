<div class="elementor-element elementor-element-68d6539 e-grid e-con-boxed e-con e-parent e-lazyloaded"
    data-id="68d6539" data-element_type="container">
    <div class="e-con-inner">
        @php
            $services = [
                [
                    'icon' => 'icon-internet',
                    'title' => 'Global Wealth Management',
                    'desc'  => 'We help individuals and institutions grow, preserve, and transfer wealth through diversified global investment strategies tailored to long-term financial goals.',
                    'animation' => 'fadeInLeft',
                    'delay' => 200,
                ],
                [
                    'icon' => 'icon-bank1',
                    'title' => 'Personal & Corporate Banking',
                    'desc'  => 'Secure banking solutions for individuals and businesses, including savings, funding, cash management, and structured financial services designed for growth.',
                    'animation' => 'fadeInLeft',
                    'delay' => null,
                ],
                [
                    'icon' => 'icon-Design-3',
                    'title' => 'Asset & Portfolio Management',
                    'desc'  => 'Professional management of investment portfolios with data-driven strategies focused on risk control, capital appreciation, and consistent returns.',
                    'animation' => 'fadeInRight',
                    'delay' => null,
                ],
                [
                    'icon' => 'icon-chart2',
                    'title' => 'Trading & Investment Solutions',
                    'desc'  => 'Access expertly managed trading and investment opportunities across markets, backed by research, analytics, and disciplined execution.',
                    'animation' => 'fadeInRight',
                    'delay' => 200,
                ],
            ];
        @endphp


        @foreach ($services as $service)
            <div class="elementor-element elementor-element-b6f5671 e-con-full e-flex e-con e-child animated fadeInLeft"
            data-id="b6f5671" data-element_type="container"
            data-settings="{&quot;animation&quot;:&quot;{{ $service['animation'] }}&quot;,&quot;animation_delay&quot;:{{ $service['delay'] }}}">
            <div class="elementor-element elementor-element-daec5a0 elementor-view-default elementor-position-top elementor-mobile-position-top elementor-widget elementor-widget-icon-box"
                data-id="daec5a0" data-element_type="widget" data-widget_type="icon-box.default">
                <div class="elementor-widget-container">
                    <div class="elementor-icon-box-wrapper">

                        <div class="elementor-icon-box-icon">
                            <span class="elementor-icon">
                                <i aria-hidden="true" class="icon {{ $service['icon'] }}"></i> </span>
                        </div>

                        <div class="elementor-icon-box-content">

                            <div class="elementor-icon-box-title">
                                <span>
                                    {{ $service['title'] }}
                                </span>
                            </div>

                            <p class="elementor-icon-box-description">
                                {{ $service['desc'] }}
                            </p>

                        </div>

                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
</div>