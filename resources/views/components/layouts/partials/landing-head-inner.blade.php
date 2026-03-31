        <meta charset="utf-8">
        <meta http-equiv="x-ua-compatible" content="ie=edge">
        <title>{{ config('app.public_name') }}</title>
        <meta name="description" content="">
        <meta name="viewport" content="width=device-width, initial-scale=1">

		<link rel="shortcut icon" type="image/x-icon" href="{{ asset('assets/img/favicon.png') }}">

        <!-- CSS here -->
        <link rel="stylesheet" href="{{ asset('new_assets/css/bootstrap.min.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/animate.min.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/fontawesome-all.min.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/mCustomScrollbar.min.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/odometer.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/slick.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/default.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/style.css') }}">
        <link rel="stylesheet" href="{{ asset('new_assets/css/responsive.css') }}">

        <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" rel="stylesheet">

        <style type="text/css" id="jarallax-clip-0">#jarallax-container-0 {
            clip: rect(0 362.3999938964844px 1293.7000732421875px 0);
            clip: rect(0, 362.3999938964844px, 1293.7000732421875px, 0);
            -webkit-clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%);
        }</style>

        <style>
            /* image wrap */

            .invora-image-wrap {
                padding: 12px;
                border-radius: 16px;

                background: rgba(255,255,255,0.03);
                backdrop-filter: blur(8px);

                border: 1px solid rgba(255,255,255,0.08);

                box-shadow: 0 20px 50px rgba(0,0,0,0.4);
            }

            .invora-image-wrap img {
                width: 100%;
                border-radius: 10px;
                display: block;
            }

            /* About INVORA AI — floating cards */
            .about-invora-cards .choose-item {
                height: 100%;
                padding: 36px 28px;
                box-shadow: 0 14px 36px rgba(0, 0, 0, 0.38);
                transition: transform 0.28s ease, box-shadow 0.28s ease, border-color 0.28s ease;
            }
            .about-invora-cards .choose-item:hover {
                transform: translateY(-6px);
                box-shadow: 0 22px 48px rgba(0, 196, 244, 0.14);
            }
            .about-invora-cards .choose-icon {
                width: 64px;
                height: 64px;
                margin-bottom: 22px;
            }
            .about-invora-cards .choose-content .title {
                font-size: 19px;
                margin-bottom: 14px;
            }
            /* Gap before CTA; z-index keeps button above card shadows */
            .about-invora-cards .about-invora-cards-grid {
                padding-bottom: 72px;
                margin-bottom: 0;
            }
            .about-invora-cards .about-invora-cta {
                margin-top: 0 !important;
                position: relative;
                z-index: 2;
            }

            /* Meet the CEO — split layout, tight vertical rhythm */
            .meet-the-ceo-split {
                padding-top: 32px !important;
                padding-bottom: 40px !important;
            }
            .meet-the-ceo-split .meet-the-ceo-head .sub-title {
                margin-bottom: 8px;
            }
            .meet-the-ceo-split .meet-the-ceo-head .title {
                margin-bottom: 18px;
            }
            .meet-the-ceo-split .team-item.meet-the-ceo-figure-wrapper {
                margin-bottom: 0 !important;
            }
            @media (min-width: 992px) {
                .meet-the-ceo-split .meet-the-ceo-head,
                .meet-the-ceo-split .meet-the-ceo-figure-wrapper {
                    text-align: left;
                }
                .meet-the-ceo-split .meet-the-ceo-bio {
                    text-align: left;
                    padding-left: 8px;
                }
                .meet-the-ceo-split .meet-the-ceo-bio .title,
                .meet-the-ceo-split .meet-the-ceo-bio span {
                    margin-left: 0;
                }
            }
            /* Ring + portrait: same outer box so ring stays centered on the image */
            .meet-the-ceo-split .meet-the-ceo-figure-wrapper .team-thumb {
                width: 210px;
                height: 210px;
                padding: 15px;
                margin-bottom: 0;
                display: inline-flex;
                align-items: center;
                justify-content: center;
                box-sizing: border-box;
            }
            .meet-the-ceo-split .meet-the-ceo-figure-wrapper .team-thumb::before {
                width: 210px;
                height: 210px;
                left: 0;
                top: 0;
                margin: 0;
                box-sizing: border-box;
            }
            .meet-the-ceo-split .meet-the-ceo-figure-wrapper .team-thumb img {
                width: 180px !important;
                height: 180px !important;
                max-width: 180px;
                object-fit: cover;
                flex-shrink: 0;
                filter: grayscale(0%) !important;
            }

            /* Our Vision — gradient frame + lead + pills */
            .invora-vision-section {
                position: relative;
            }
            .invora-vision-section::before {
                content: "";
                position: absolute;
                left: 50%;
                top: 50%;
                transform: translate(-50%, -40%);
                width: min(720px, 90vw);
                height: 280px;
                background: radial-gradient(ellipse at center, rgba(0, 196, 244, 0.12) 0%, transparent 70%);
                pointer-events: none;
                z-index: 0;
            }
            .invora-vision-section .container {
                position: relative;
                z-index: 1;
            }
            .invora-vision-wrap {
                border-radius: 20px;
                padding: 2px;
                background: linear-gradient(
                    135deg,
                    rgba(0, 196, 244, 0.55)
                        0%,
                    rgba(0, 176, 139, 0.35)
                        45%,
                    rgba(0, 196, 244, 0.2)
                        100%
                );
                box-shadow:
                    0 28px 64px rgba(0, 0, 0, 0.45),
                    inset 0 1px 0 rgba(255, 255, 255, 0.06);
            }
            .invora-vision-inner {
                border-radius: 18px;
                background: linear-gradient(
                    165deg,
                    rgba(15, 28, 48, 0.97)
                        0%,
                    rgba(5, 10, 18, 0.99)
                        100%
                );
                border: 1px solid rgba(255, 255, 255, 0.06);
                padding: 2.25rem 1.75rem 1.75rem;
            }
            @media (min-width: 768px) {
                .invora-vision-inner {
                    padding: 2.75rem 2.5rem 2.25rem;
                }
            }
            .invora-vision-icon {
                width: 56px;
                height: 56px;
                border-radius: 14px;
                display: flex;
                align-items: center;
                justify-content: center;
                margin-bottom: 1.25rem;
                background: rgba(0, 196, 244, 0.1);
                border: 1px solid rgba(0, 196, 244, 0.22);
                color: #00c4f4;
                font-size: 1.35rem;
            }
            .invora-vision-lead {
                font-size: 1.125rem;
                line-height: 1.75;
                color: #e6edf3;
                margin-bottom: 0;
                font-weight: 500;
                letter-spacing: -0.01em;
            }
            @media (min-width: 768px) {
                .invora-vision-lead {
                    font-size: 1.22rem;
                }
            }
            .invora-vision-rule {
                height: 2px;
                margin: 1.5rem 0;
                border: 0;
                border-radius: 2px;
                background: linear-gradient(
                    90deg,
                    transparent
                        0%,
                    rgba(0, 196, 244, 0.45)
                        35%,
                    rgba(0, 176, 139, 0.4)
                        65%,
                    transparent
                        100%
                );
            }
            .invora-vision-body {
                color: #a4b4c3;
                font-size: 1rem;
                line-height: 1.75;
                margin-bottom: 0;
            }
            .invora-vision-pills {
                display: flex;
                flex-wrap: wrap;
                gap: 0.6rem 0.75rem;
                margin-top: 1.5rem;
                padding-top: 1.25rem;
                border-top: 1px solid rgba(255, 255, 255, 0.06);
            }
            .invora-vision-pills span {
                display: inline-flex;
                align-items: center;
                gap: 0.45rem;
                font-size: 0.85rem;
                font-weight: 500;
                color: #c5d9e8;
                padding: 0.45rem 0.85rem;
                border-radius: 999px;
                background: rgba(0, 196, 244, 0.06);
                border: 1px solid rgba(0, 196, 244, 0.22);
            }
            .invora-vision-pills span i {
                color: #00b08b;
                font-size: 0.85rem;
                opacity: 0.95;
            }
        </style>
