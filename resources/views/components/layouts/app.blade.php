
<!DOCTYPE html>
<html lang="en" class="dark dark">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>{{ env('APP_NAME') }}</title>
        <link rel="icon" type="image/png" href="assets/images/favicon.png" sizes="16x16">
        <!-- google fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&amp;display=swap" rel="stylesheet">

        <!-- remix icon font css  -->
        <link rel="stylesheet" href="{{ asset('assets/css/remixicon.css') }}">
        <!-- Apex Chart css -->
        <link rel="stylesheet" href="{{ asset('assets/css/lib/apexcharts.css') }}">
        <!-- Data Table css -->
        <!-- <link rel="stylesheet" href="{{ asset('assets/css/lib/dataTables.min.css') }}"> -->
        <!-- Text Editor css -->
        <link rel="stylesheet" href="{{ asset('assets/css/lib/editor-katex.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/lib/editor.atom-one-dark.min.css') }}">
        <link rel="stylesheet" href="{{ asset('assets/css/lib/editor.quill.snow.css') }}">
        <!-- Date picker css -->
        <link rel="stylesheet" href="{{ asset('assets/css/lib/flatpickr.min.css') }}">
        <!-- Calendar css -->
        <link rel="stylesheet" href="{{ asset('assets/css/lib/full-calendar.css') }}">
        <!-- Vector Map css -->
        <link rel="stylesheet" href="{{ asset('assets/css/lib/jquery-jvectormap-2.0.5.css') }}">
        <!-- Popup css -->
        <link rel="stylesheet" href="{{ asset('assets/css/lib/magnific-popup.css') }}">
        <!-- Slick Slider css -->
        <link rel="stylesheet" href="{{ asset('assets/css/lib/slick.css') }}">
        <!-- prism css -->
        <link rel="stylesheet" href="{{ asset('assets/css/lib/prism.css') }}">
        <!-- file upload css -->
        <link rel="stylesheet" href="{{ asset('assets/css/lib/file-upload.css') }}">
        
        <link rel="stylesheet" href="{{ asset('assets/css/lib/audioplayer.css') }}">
        <!-- main css -->
        <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
        @livewireStyles()

        @vite([
            'resources/css/deposit.css', 
            'resources/css/app.css', 
            'resources/css/investment.css',
            'resources/css/invora-ui.css',
            'resources/css/investment-item.css',
            'resources/css/profile.css',
            'resources/css/settings.css',
            'resources/css/referral-overview.css',
            'resources/css/referral-bonus.css',
            'resources/css/referral-direct.css',
            'resources/css/tickets.css',
            'resources/css/bot.css',
        ])
        <link rel="stylesheet" href="https://unpkg.com/intro.js/introjs.css">
        <script src="https://unpkg.com/intro.js/intro.js"></script>

        @stack('styles')
    </head>

    <body class="dark:bg-neutral-800 bg-neutral-100 dark:text-white">
        @include('components.layouts.app.aside')
        <main class="dashboard-main">
            @include('components.layouts.app.header')
            <div class="dashboard-main-body">

                <div class="hidden flex-wrap items-center justify-between gap-2 mb-6">
                    <h6 class="font-semibold mb-0 dark:text-white">
                        {{ $title ?? 'Dashboard' }}
                    </h6>

                    <ul class="flex items-center gap-[6px]">
                        <li class="font-medium">
                            <a href="{{ route('dashboard') }}"
                                class="flex items-center gap-2 text-neutral-600 hover:text-primary-600 dark:text-white dark:hover:text-primary-600">
                                <iconify-icon icon="solar:home-smile-angle-outline" class="icon text-lg"></iconify-icon>
                                Dashboard
                            </a>
                        </li>
                    </ul>
                </div>

                @if (session()->has('success'))
                    <div class="invora-toast success" id="invoraToast">
                        <div class="toast-icon">✓</div>
                        <div class="toast-content">
                            <div class="toast-title">Success</div>
                            <div class="toast-message">
                                {{ session('success') }}
                            </div>
                        </div>
                    </div>
                @endif

                <div 
                    x-data="{ show: false, message: '' }"
                    x-on:success.window="
                        show = true;
                        message = $event.detail.message;
                        setTimeout(() => show = false, 3000);
                    "
                    x-show="show"
                    x-cloak
                    class="invora-toast success"
                >
                    <div class="toast-icon">✓</div>
                    <div class="toast-content">
                        <div class="toast-title">Success</div>
                        <div x-text="message" class="toast-message"></div>
                    </div>
                </div>

                <div 
                    x-data="{ show: false, message: '', timeout: null }"
                    x-on:error.window="
                        if (timeout) clearTimeout(timeout);
                        show = true;
                        message = $event.detail.message;
                        timeout = setTimeout(() => show = false, 3000);
                    "
                    x-show="show"
                    x-cloak
                    class="invora-toast error"
                >
                    <div class="toast-icon">✓</div>
                    <div class="toast-content">
                        <div class="toast-title">Error</div>
                        <div x-text="message" class="toast-message"></div>
                    </div>
                </div>
                {{ $slot }}
            </div>
            
        </main>
        
        @include('components.layouts.app.general-scripts')
        @livewireScripts
        @stack('scripts')

        <!-- Toast -->
        <script>
        (function () {
            if (!document.querySelector('.halpha-toast-container')) {
                const c = document.createElement('div');
                c.className = 'halpha-toast-container';
                document.body.appendChild(c);
            }
            const container = document.querySelector('.halpha-toast-container');

            function createToastElement(message, { variant = 'primary', timeout = 3500 } = {}) {
                const t = document.createElement('div');
                t.className = 'halpha-toast' + (variant === 'subtle' ? ' halpha-toast--subtle' : '');
                t.setAttribute('role', 'status');
                t.innerHTML = `<div class="halpha-toast__message">${escapeHtml(message)}</div>`;

                // close button
                const btn = document.createElement('button');
                btn.className = 'halpha-toast__close';
                btn.setAttribute('aria-label', 'Dismiss toast');
                btn.innerHTML = '✕';
                btn.addEventListener('click', () => removeToast(t));
                t.appendChild(btn);

                // auto remove after timeout
                const timer = setTimeout(() => removeToast(t), timeout);

                // store timer so we can clear if user dismisses early
                t._halpha_timer = timer;
                return t;
            }

            function showToast(message, options = {}) {
                const toastEl = createToastElement(message, options);
                if (container.firstChild) container.insertBefore(toastEl, container.firstChild);
                else container.appendChild(toastEl);

                requestAnimationFrame(() => {
                    toastEl.classList.add('halpha-toast--show');
                });

                return toastEl;
            }

            function removeToast(el) {
                if (!el) return;
                if (el._halpha_timer) clearTimeout(el._halpha_timer);
                el.style.transition = 'transform 260ms ease, opacity 260ms ease';
                el.style.transform = 'translateY(-18px)';
                el.style.opacity = '0';
                // remove after transition
                setTimeout(() => {
                    if (el && el.remove) el.remove();
                }, 300);
            }

            function escapeHtml(str) {
                if (typeof str !== 'string') return String(str);
                return str
                    .replaceAll('&', '&amp;')
                    .replaceAll('<', '&lt;')
                    .replaceAll('>', '&gt;')
                    .replaceAll('"', '&quot;')
                    .replaceAll("'", '&#39;');
            }

            if (window.Livewire) {
                Livewire.on('toast', ({ payload }) => {
                    let message = '';
                    let opts = {};
                    if (typeof payload === 'string') {
                        message = payload;
                    } else if (payload && typeof payload === 'object') {
                        message = payload.message;
                        if (payload.variant) opts.variant = payload.variant;
                        if (payload.timeout) opts.timeout = Number(payload.timeout) || 3500;
                    }
                    if (!message) return;
                    showToast(message, opts);
                });
            }
        })();
        </script>

        <script>
            (function () {

                const enableDark = () => {
                    if (!document.documentElement.classList.contains('dark')) {
                        document.documentElement.classList.add('dark');
                    }
                };

                // Force immediately
                enableDark();

                // Observe changes to <html> class attribute
                const observer = new MutationObserver(function (mutations) {
                    mutations.forEach(function (mutation) {
                        if (mutation.attributeName === "class") {
                            enableDark();
                        }
                    });
                });

                observer.observe(document.documentElement, {
                    attributes: true,
                    attributeFilter: ['class']
                });

            })();
        </script>

        <script>
            setTimeout(() => {
                const toast = document.getElementById('invoraToast');
                if (toast) toast.remove();
            }, 5000);
        </script>

        @include('components.layouts.live-chat')


        <livewire:milestone-popup />

    </body>
</html>