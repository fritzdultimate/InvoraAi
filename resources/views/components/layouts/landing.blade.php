<!doctype html>
<html class="no-js" lang="en">

<head>
    @include('components.layouts.partials.landing-head-inner')

    <style>
        .invora-lang-trigger {
            display: flex;
            align-items: center;
            gap: 8px;
            background: rgba(255, 255, 255, 0.04);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 8px;
            padding: 8px 14px;
            cursor: pointer;
            transition: all 0.2s;
            color: #fff;
            font-size: 13px;
            font-weight: 600;
            letter-spacing: 0.08em;
            user-select: none;
        }

        .invora-lang-trigger:hover,
        .invora-lang-trigger.open {
            background: rgba(0, 229, 192, 0.08);
            border-color: rgba(0, 229, 192, 0.5);
        }

        .invora-lang-chevron {
            width: 14px;
            height: 14px;
            opacity: 0.6;
            transition: transform 0.25s;
        }

        .invora-lang-trigger.open .invora-lang-chevron {
            transform: rotate(180deg);
        }

        .invora-lang-dropdown {
            position: absolute;
            top: calc(100% + 10px);
            right: 0;
            background: #111827;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 12px;
            min-width: 210px;
            overflow: hidden;
            opacity: 0;
            transform: translateY(-8px) scale(0.97);
            pointer-events: none;
            transition: all 0.2s cubic-bezier(0.16, 1, 0.3, 1);
            z-index: 9999;
            box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6);
        }

        .invora-lang-dropdown.visible {
            opacity: 1;
            transform: translateY(0) scale(1);
            pointer-events: all;
        }

        .invora-lang-dropdown-header {
            padding: 10px 14px 8px;
            font-size: 10px;
            font-weight: 700;
            letter-spacing: 0.12em;
            color: rgba(255, 255, 255, 0.3);
            text-transform: uppercase;
            border-bottom: 1px solid rgba(255, 255, 255, 0.06);
        }

        .invora-lang-option {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 11px 14px;
            cursor: pointer;
            transition: background 0.15s;
            position: relative;
        }

        .invora-lang-option:hover {
            background: rgba(255, 255, 255, 0.05);
        }

        .invora-lang-option.active {
            background: rgba(0, 229, 192, 0.07);
        }

        .invora-lang-option.active::before {
            content: '';
            position: absolute;
            left: 0;
            top: 20%;
            height: 60%;
            width: 2.5px;
            background: #00e5c0;
            border-radius: 0 2px 2px 0;
        }

        .invora-lang-emoji {
            font-size: 20px;
        }

        .invora-lang-name {
            font-size: 13px;
            font-weight: 500;
            color: #e5e7eb;
            line-height: 1.2;
        }

        .invora-lang-native {
            font-size: 11px;
            color: rgba(255, 255, 255, 0.35);
        }

        .invora-lang-check {
            margin-left: auto;
            color: #00e5c0;
            opacity: 0;
            width: 16px;
            height: 16px;
        }

        .invora-lang-option.active .invora-lang-check {
            opacity: 1;
        }

        .invora-lang-divider {
            height: 1px;
            background: rgba(255, 255, 255, 0.06);
            margin: 4px 0;
        }
    </style>
</head>

<body class="home-01">
    @if($showPreloader ?? false)
        @include('components.layouts.landing.preloader')
    @endif
    @include('components.layouts.landing.header')

    {{ $slot }}

    @include('components.layouts.landing.footer')
    @include('components.layouts.landing.footer-scripts')
    @include('components.layouts.live-chat')

    @include('components.layouts.landing.translator')
    <script>
        (function () {
            const trigger = document.getElementById('invorLangTrigger');
            const dropdown = document.getElementById('invorLangDropdown');
            const switcher = document.getElementById('invorLangSwitcher');


            trigger.addEventListener('click', () => {
                trigger.classList.toggle('open');
                dropdown.classList.toggle('visible');
            });

            document.addEventListener('click', e => {
                if (!switcher.contains(e.target)) {
                    trigger.classList.remove('open');
                    dropdown.classList.remove('visible');
                }
            });

            dropdown.querySelectorAll('.invora-lang-option').forEach(opt => {
                opt.addEventListener('click', () => {
                    dropdown.querySelectorAll('.invora-lang-option').forEach(o => o.classList.remove('active'));
                    opt.classList.add('active');
                    document.getElementById('invorLangFlag').textContent = opt.dataset.flag;
                    document.getElementById('invorLangCode').textContent = opt.dataset.code;
                    trigger.classList.remove('open');
                    dropdown.classList.remove('visible');

                    // Trigger Google Translate
                    const gtCode = opt.dataset.gt;
                    const select = document.querySelector('.goog-te-combo');
                    if (select) {
                        select.value = gtCode;
                        select.dispatchEvent(new Event('change'));
                    }
                });
            });
        })();
    </script>

    <script>
        (function () {
            const trigger = document.getElementById('invorLangTriggerMobile');
            const dropdown = document.getElementById('invorLangDropdownMobile');
            const switcher = document.getElementById('invorLangSwitcherMobile');


            trigger.addEventListener('click', () => {
                trigger.classList.toggle('open');
                dropdown.classList.toggle('visible');
            });

            document.addEventListener('click', e => {
                if (!switcher.contains(e.target)) {
                    trigger.classList.remove('open');
                    dropdown.classList.remove('visible');
                }
            });

            dropdown.querySelectorAll('.invora-lang-option').forEach(opt => {
                opt.addEventListener('click', () => {
                    dropdown.querySelectorAll('.invora-lang-option').forEach(o => o.classList.remove('active'));
                    opt.classList.add('active');
                    document.getElementById('invorLangFlag').textContent = opt.dataset.flag;
                    document.getElementById('invorLangCode').textContent = opt.dataset.code;
                    trigger.classList.remove('open');
                    dropdown.classList.remove('visible');

                    // Trigger Google Translate
                    const gtCode = opt.dataset.gt;
                    const select = document.querySelector('.goog-te-combo');
                    if (select) {
                        select.value = gtCode;
                        select.dispatchEvent(new Event('change'));
                    }
                });
            });
        })();
    </script>
</body>

</html>