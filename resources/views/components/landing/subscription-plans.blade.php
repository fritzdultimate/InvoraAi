<div data-elementor-type="wp-page" data-elementor-id="295" class="elementor elementor-295"
    data-elementor-post-type="page">
    <div class="elementor-element elementor-element-5c24357 e-flex e-con-boxed e-con e-parent" data-id="5c24357"
        data-element_type="container" data-settings="{&quot;background_background&quot;:&quot;classic&quot;}">
        <div class="e-con-inner">
            <div class="elementor-element elementor-element-0565c22 e-flex e-con-boxed e-con e-child" data-id="0565c22"
                data-element_type="container">
                <div class="e-con-inner">
                    <div class="elementor-element elementor-element-b1fc91f elementor-invisible elementor-widget elementor-widget-heading"
                        data-id="b1fc91f" data-element_type="widget"
                        data-settings="{&quot;_animation_delay&quot;:200,&quot;_animation&quot;:&quot;fadeInUp&quot;}"
                        data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <h6 class="elementor-heading-title elementor-size-default">Choose Package</h6>
                        </div>
                    </div>
                    <div class="elementor-element elementor-element-f8d3e0b elementor-invisible elementor-widget elementor-widget-heading"
                        data-id="f8d3e0b" data-element_type="widget"
                        data-settings="{&quot;_animation&quot;:&quot;fadeInUp&quot;,&quot;_animation_delay&quot;:300}"
                        data-widget_type="heading.default">
                        <div class="elementor-widget-container">
                            <h1 class="elementor-heading-title elementor-size-default">
                                Subscribe to Intelligent Trading Bots and Earn Daily Interest
                            </h1>
                        </div>
                    </div>
                </div>
            </div>
            <div>
                <div 
                    class="elementor-element elementor-element-ffec643 e-con-full e-grid e-con e-child" 
                    data-id="ffec643"
                    data-element_type="container"
                >

                @php
                    $plans = [
                        [
                            'id' => 212,
                            'name' => 'Smart Bot',
                            'price' => 100,
                            'popular' => false,
                            'details' => [
                                'Minimum Amount' => '$100',
                                'Maximum Amount' => '$1,000',
                                'Daily Interest Rate' => '1.2% – 1.5%',
                                'Trading Strategy' => 'Low-Risk AI Strategy',
                                'Earnings' => 'Credited Daily',
                                'Duration' => '30 Days',
                            ],
                            'features' => [
                                'AI Auto Trading',
                                'Daily Profit Settlement',
                                'Capital Protection',
                                '24/7 Monitoring',
                                'Instant Withdrawal',
                            ],
                        ],
                        [
                            'id' => 432,
                            'name' => 'Brilliant Bot',
                            'price' => 99,
                            'popular' => true,
                            'details' => [
                                'Minimum Amount' => '$500',
                                'Maximum Amount' => '$5,000',
                                'Daily Interest Rate' => '1.8% – 2.2%',
                                'Trading Strategy' => 'Balanced AI Growth',
                                'Earnings' => 'Credited Daily',
                                'Duration' => '30 Days',
                            ],
                            'features' => [
                                'Advanced AI Trading',
                                'Higher Profit Margin',
                                'Risk Management System',
                                'Daily Compounding',
                                'Priority Support',
                            ],
                        ],
                        [
                            'id' => 4934,
                            'name' => 'Genius Bot',
                            'price' => 149,
                            'popular' => false,
                            'details' => [
                                'Minimum Amount' => '$1,000',
                                'Maximum Amount' => '$15,000',
                                'Daily Interest Rate' => '2.5% – 3.0%',
                                'Trading Strategy' => 'Advanced AI Intelligence',
                                'Earnings' => 'Credited Daily',
                                'Duration' => '30 Days',
                            ],
                            'features' => [
                                'Elite AI Algorithm',
                                'Maximum ROI Optimization',
                                'Multi-Market Strategy',
                                'Auto Risk Hedging',
                                'VIP Support Access',
                            ],
                        ],
                    ];
                @endphp


                    @foreach ($plans as $plan)
                        <div 
                            class="elementor-element {{ $plan['popular'] ? 'elementor-element-26519ae' : 'elementor-element-5d94182' }} e-con-full e-flex elementor-invisible e-con e-child" data-settings='{
                                "background_background":"{{ $plan["popular"] ? "gradient" : "classic" }}",
                                "animation":"fadeInUp",
                                "animation_delay":"200"
                            }'
                            data-element_type="container"
                            data-id="bot-{{ $plan['id'] }}"
                        >

                            {{-- Popular badge --}}
                            @if($plan['popular'])
                                <div 
                                    class="elementor-element elementor-element-2e31ea5 elementor-absolute elementor-widget elementor-widget-heading"
                                    data-id="2e31ea5" 
                                    data-element_type="widget"
                                    data-settings="{&quot;_position&quot;:&quot;absolute&quot;}" data-widget_type="heading.default"
                                >
                                    <div class="elementor-widget-container">
                                        <h6 class="elementor-heading-title elementor-size-default">Most Popular</h6>
                                    </div>
                                </div>
                            @endif

                            {{-- Header --}}
                            <div 
                                class="elementor-element {{ $plan['popular'] ? 'elementor-element-e15a1d5' : 'elementor-element-a5b39af' }} e-con-full e-flex e-con e-child"
                                data-id="bot-{{ $plan['id'] }}" 
                                data-element_type="container"
                            >
                                <div 
                                    class="elementor-element {{ $plan['popular'] ? 'elementor-element-21cfafc' : 'elementor-element-3c0e38f' }} elementor-widget elementor-widget-heading"
                                    data-id="{{ $plan['popular'] ? '21cfafc' : '3c0e38f' }}" 
                                    data-element_type="widget" 
                                    data-widget_type="heading.default"
                                >
                                    <div class="elementor-widget-container">
                                        <h6 class="elementor-heading-title elementor-size-default">{{ $plan['name'] }}</h6>
                                    </div>
                                </div>
                                <div class="elementor-element {{ $plan['popular'] ? 'elementor-element-0fece19' : 'elementor-element-ef9335b' }} elementor-widget elementor-widget-heading"
                                    data-id="ef9335b" data-element_type="widget" data-widget_type="heading.default">
                                    <div class="elementor-widget-container">
                                        <div class="elementor-heading-title elementor-size-default">${{ $plan['price'] }}</div>
                                    </div>
                                </div>
                                <div class="elementor-element {{ $plan['popular'] ? 'elementor-element-f292adb' : 'elementor-element-c2a580d' }} elementor-widget elementor-widget-heading"
                                    data-id="c2a580d" data-element_type="widget" data-widget_type="heading.default">
                                    <div class="elementor-widget-container">
                                        <div class="elementor-heading-title elementor-size-default">/monthly</div>
                                    </div>
                                </div>
                            </div>

                            {{-- Details --}}
                            <div class="elementor-element elementor-element-2044f60 e-con-full e-flex e-con e-child"
                            data-id="2044f60" data-element_type="container">
                                <div class="elementor-element elementor-element-c3ca9a6 elementor-widget elementor-widget-heading"
                                    data-id="c3ca9a6" data-element_type="widget" data-widget_type="heading.default">
                                    <div class="elementor-widget-container">
                                        <h5 class="elementor-heading-title elementor-size-default">Features :</h5>
                                    </div>
                                </div>
                                <div 
                                    class="elementor-element {{ $plan['popular'] ? 'elementor-element-467517a' : 'elementor-element-73e7d95' }} elementor-icon-list--layout-traditional elementor-list-item-link-full_width elementor-widget elementor-widget-icon-list"
                                    data-id="73e7d95" 
                                    data-element_type="widget" 
                                    data-widget_type="icon-list.default"
                                >
                                    <div class="elementor-widget-container">
                                        <ul class="elementor-icon-list-items">

                                            @foreach ($plan['details'] as $label => $value)
                                                <li class="elementor-icon-list-item">
                                                    <span class="elementor-icon-list-icon">
                                                        <i class="mdi mdi-star-circle"></i>
                                                    </span>
                                                    <span class="elementor-icon-list-text">
                                                        <strong>{{ $label }}:</strong> {{ $value }}
                                                    </span>
                                                </li>
                                            @endforeach

                                            {{-- Divider spacing --}}
                                            <li class="elementor-icon-list-item" style="opacity:0.4;"></li>


                                            @foreach ($plan['features'] as $label => $feature)
                                                <li class="elementor-icon-list-item">
                                                    <span class="elementor-icon-list-icon">
                                                        <i aria-hidden="true" class="mdi mdi-checkbox-marked-circle"></i> </span>
                                                    <span class="elementor-icon-list-text">{{ $feature }}</span>
                                                </li>
                                            @endforeach
                                        </ul>
                                    </div>
                                </div>
                            </div>

                            {{-- Button --}}
                            <div class="elementor-element {{ $plan['popular'] ? 'elementor-element-d9f1844' : 'elementor-element-7b6905d' }} elementor-align-justify elementor-widget elementor-widget-button"
                            data-id="d9f1844" data-element_type="widget" data-widget_type="button.default">
                                <div class="elementor-widget-container">
                                    <div class="elementor-button-wrapper">
                                        <a class="elementor-button elementor-button-link elementor-size-sm" href="#">
                                            <span class="elementor-button-content-wrapper">
                                                <span class="elementor-button-icon">
                                                    <svg aria-hidden="true" class="e-font-icon-svg e-fas-arrow-right"
                                                        viewBox="0 0 448 512" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M190.5 66.9l22.2-22.2c9.4-9.4 24.6-9.4 33.9 0L441 239c9.4 9.4 9.4 24.6 0 33.9L246.6 467.3c-9.4 9.4-24.6 9.4-33.9 0l-22.2-22.2c-9.5-9.5-9.3-25 .4-34.3L311.4 296H24c-13.3 0-24-10.7-24-24v-32c0-13.3 10.7-24 24-24h287.4L190.9 101.2c-9.8-9.3-10-24.8-.4-34.3z">
                                                        </path>
                                                    </svg> </span>
                                                <span class="elementor-button-text">Activate Smart Bot</span>
                                            </span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            
                        </div>
                    @endforeach

                </div>
            </div>
        </div>
    </div>
</div>