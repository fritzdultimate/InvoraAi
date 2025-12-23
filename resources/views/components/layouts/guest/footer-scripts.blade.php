<script type="speculationrules">
{"prefetch":[{"source":"document","where":{"and":[{"href_matches":"/capwise/*"},{"not":{"href_matches":["/capwise/wp-*.php","/capwise/wp-admin/*","/capwise/wp-content/uploads/sites/9/*","/capwise/wp-content/*","/capwise/wp-content/plugins/*","/capwise/wp-content/themes/hello-elementor/*","/capwise/*\\?(.+)"]}},{"not":{"selector_matches":"a[rel~=\"nofollow\"]"}},{"not":{"selector_matches":".no-prefetch, .no-prefetch a"}}]},"eagerness":"conservative"}]}
</script>
<script>
    const lazyloadRunObserver = () => {
        const lazyloadBackgrounds = document.querySelectorAll(`.e-con.e-parent:not(.e-lazyloaded)`);
        const lazyloadBackgroundObserver = new IntersectionObserver((entries) => {
            entries.forEach((entry) => {
                if (entry.isIntersecting) {
                    let lazyloadBackground = entry.target;
                    if (lazyloadBackground) {
                        lazyloadBackground.classList.add('e-lazyloaded');
                    }
                    lazyloadBackgroundObserver.unobserve(entry.target);
                }
            });
        }, { rootMargin: '200px 0px 200px 0px' });
        lazyloadBackgrounds.forEach((lazyloadBackground) => {
            lazyloadBackgroundObserver.observe(lazyloadBackground);
        });
    };
    const events = [
        'DOMContentLoaded',
        'elementor/lazyload/observe',
    ];
    events.forEach((event) => {
        document.addEventListener(event, lazyloadRunObserver);
    });
</script>
<script src="{{ asset('wp-content/themes/hello-elementor/assets/js/hello-frontendedb1.js') }}"
    id="hello-theme-frontend-js"></script>
<script src="{{ asset('wp-content/plugins/elementor/assets/js/webpack.runtime.min37de.js') }}"
    id="elementor-webpack-runtime-js"></script>
<script src="{{ asset('wp-content/plugins/elementor/assets/js/frontend-modules.min37de.js') }}"
    id="elementor-frontend-modules-js"></script>
<script src="{{ asset('wp-includes/js/jquery/ui/core.minb37e.js') }}" id="jquery-ui-core-js"></script>
<script id="elementor-frontend-js-before">
    var elementorFrontendConfig = { "environmentMode": { "edit": false, "wpPreview": false, "isScriptDebug": false }, "i18n": { "shareOnFacebook": "Share on Facebook", "shareOnTwitter": "Share on Twitter", "pinIt": "Pin it", "download": "Download", "downloadImage": "Download image", "fullscreen": "Fullscreen", "zoom": "Zoom", "share": "Share", "playVideo": "Play Video", "previous": "Previous", "next": "Next", "close": "Close", "a11yCarouselPrevSlideMessage": "Previous slide", "a11yCarouselNextSlideMessage": "Next slide", "a11yCarouselFirstSlideMessage": "This is the first slide", "a11yCarouselLastSlideMessage": "This is the last slide", "a11yCarouselPaginationBulletMessage": "Go to slide" }, "is_rtl": false, "breakpoints": { "xs": 0, "sm": 480, "md": 768, "lg": 1025, "xl": 1440, "xxl": 1600 }, "responsive": { "breakpoints": { "mobile": { "label": "Mobile Portrait", "value": 767, "default_value": 767, "direction": "max", "is_enabled": true }, "mobile_extra": { "label": "Mobile Landscape", "value": 880, "default_value": 880, "direction": "max", "is_enabled": false }, "tablet": { "label": "Tablet Portrait", "value": 1024, "default_value": 1024, "direction": "max", "is_enabled": true }, "tablet_extra": { "label": "Tablet Landscape", "value": 1200, "default_value": 1200, "direction": "max", "is_enabled": false }, "laptop": { "label": "Laptop", "value": 1366, "default_value": 1366, "direction": "max", "is_enabled": false }, "widescreen": { "label": "Widescreen", "value": 2400, "default_value": 2400, "direction": "min", "is_enabled": false } }, "hasCustomBreakpoints": false }, "version": "3.33.4", "is_static": false, "experimentalFeatures": { "e_font_icon_svg": true, "additional_custom_breakpoints": true, "container": true, "theme_builder_v2": true, "hello-theme-header-footer": true, "nested-elements": true, "home_screen": true, "global_classes_should_enforce_capabilities": true, "e_variables": true, "cloud-library": true, "e_opt_in_v4_page": true, "import-export-customization": true, "e_pro_variables": true }, "urls": { "assets": "https:\/\/demokit.creativemox.com\/capwise\/wp-content\/plugins\/elementor\/assets\/", "ajaxurl": "https:\/\/demokit.creativemox.com\/capwise\/wp-admin\/admin-ajax.php", "uploadUrl": "http:\/\/demokit.creativemox.com\/capwise\/wp-content\/uploads\/sites\/9" }, "nonces": { "floatingButtonsClickTracking": "818cfd28eb" }, "swiperClass": "swiper", "settings": { "page": [], "editorPreferences": [] }, "kit": { "body_background_background": "classic", "active_breakpoints": ["viewport_mobile", "viewport_tablet"], "global_image_lightbox": "yes", "lightbox_enable_counter": "yes", "lightbox_enable_fullscreen": "yes", "lightbox_enable_zoom": "yes", "lightbox_enable_share": "yes", "lightbox_title_src": "title", "lightbox_description_src": "description", "hello_header_logo_type": "title", "hello_header_menu_layout": "horizontal", "hello_footer_logo_type": "logo" }, "post": { "id": 681, "title": "Capwise", "excerpt": "", "featuredImage": false } };
    //# sourceURL=elementor-frontend-js-before
</script>
<script src="{{ asset('wp-content/plugins/elementor/assets/js/frontend.min37de.js') }}" id="elementor-frontend-js"></script>
<script src="{{ asset('wp-content/plugins/elementor-pro/assets/lib/smartmenus/jquery.smartmenus.min1576.js') }}" id="smartmenus-js"></script>
<script id="mediaelement-core-js-before">
    var mejsL10n = { "language": "en", "strings": { "mejs.download-file": "Download File", "mejs.install-flash": "You are using a browser that does not have Flash player enabled or installed. Please turn on your Flash player plugin or download the latest version from https://get.adobe.com/flashplayer/", "mejs.fullscreen": "Fullscreen", "mejs.play": "Play", "mejs.pause": "Pause", "mejs.time-slider": "Time Slider", "mejs.time-help-text": "Use Left/Right Arrow keys to advance one second, Up/Down arrows to advance ten seconds.", "mejs.live-broadcast": "Live Broadcast", "mejs.volume-help-text": "Use Up/Down Arrow keys to increase or decrease volume.", "mejs.unmute": "Unmute", "mejs.mute": "Mute", "mejs.volume-slider": "Volume Slider", "mejs.video-player": "Video Player", "mejs.audio-player": "Audio Player", "mejs.captions-subtitles": "Captions/Subtitles", "mejs.captions-chapters": "Chapters", "mejs.none": "None", "mejs.afrikaans": "Afrikaans", "mejs.albanian": "Albanian", "mejs.arabic": "Arabic", "mejs.belarusian": "Belarusian", "mejs.bulgarian": "Bulgarian", "mejs.catalan": "Catalan", "mejs.chinese": "Chinese", "mejs.chinese-simplified": "Chinese (Simplified)", "mejs.chinese-traditional": "Chinese (Traditional)", "mejs.croatian": "Croatian", "mejs.czech": "Czech", "mejs.danish": "Danish", "mejs.dutch": "Dutch", "mejs.english": "English", "mejs.estonian": "Estonian", "mejs.filipino": "Filipino", "mejs.finnish": "Finnish", "mejs.french": "French", "mejs.galician": "Galician", "mejs.german": "German", "mejs.greek": "Greek", "mejs.haitian-creole": "Haitian Creole", "mejs.hebrew": "Hebrew", "mejs.hindi": "Hindi", "mejs.hungarian": "Hungarian", "mejs.icelandic": "Icelandic", "mejs.indonesian": "Indonesian", "mejs.irish": "Irish", "mejs.italian": "Italian", "mejs.japanese": "Japanese", "mejs.korean": "Korean", "mejs.latvian": "Latvian", "mejs.lithuanian": "Lithuanian", "mejs.macedonian": "Macedonian", "mejs.malay": "Malay", "mejs.maltese": "Maltese", "mejs.norwegian": "Norwegian", "mejs.persian": "Persian", "mejs.polish": "Polish", "mejs.portuguese": "Portuguese", "mejs.romanian": "Romanian", "mejs.russian": "Russian", "mejs.serbian": "Serbian", "mejs.slovak": "Slovak", "mejs.slovenian": "Slovenian", "mejs.spanish": "Spanish", "mejs.swahili": "Swahili", "mejs.swedish": "Swedish", "mejs.tagalog": "Tagalog", "mejs.thai": "Thai", "mejs.turkish": "Turkish", "mejs.ukrainian": "Ukrainian", "mejs.vietnamese": "Vietnamese", "mejs.welsh": "Welsh", "mejs.yiddish": "Yiddish" } };
    //# sourceURL=mediaelement-core-js-before
</script>
<script src="{{ asset('wp-includes/js/mediaelement/mediaelement-and-player.min1f61.js') }}" id="mediaelement-core-js"></script>
<script src="{{ asset('wp-includes/js/mediaelement/mediaelement-migrate.mind4d0.js') }}" id="mediaelement-migrate-js"></script>
<script id="mediaelement-js-extra">
    var _wpmejsSettings = { "pluginPath": "/capwise/wp-includes/js/mediaelement/", "classPrefix": "mejs-", "stretching": "responsive", "audioShortcodeLibrary": "mediaelement", "videoShortcodeLibrary": "mediaelement" };
    //# sourceURL=mediaelement-js-extra
</script>
<script src="{{ asset('wp-includes/js/mediaelement/wp-mediaelement.mind4d0.js') }}" id="wp-mediaelement-js"></script>
<script src="{{ asset('wp-content/plugins/elementor/assets/lib/jquery-numerator/jquery-numerator.min3958.js') }}" id="jquery-numerator-js"></script>
<script src="{{ asset('wp-content/plugins/elementor/assets/lib/swiper/v8/swiper.min94a4.js') }}" id="swiper-js"></script>
<script src="{{ asset('wp-content/plugins/elementskit-lite/libs/framework/assets/js/frontend-scriptac9e.js') }}" id="elementskit-framework-js-frontend-js"></script>
<script id="elementskit-framework-js-frontend-js-after">
    var elementskit = {
        resturl: 'https://demokit.creativemox.com/capwise/wp-json/elementskit/v1/',
    }


    //# sourceURL=elementskit-framework-js-frontend-js-after
</script>
<script src="{{ asset('wp-content/plugins/elementskit-lite/widgets/init/assets/js/widget-scriptsac9e.js') }}" id="ekit-widget-scripts-js"></script>
<script id="smush-lazy-load-js-before">
    var smushLazyLoadOptions = { "autoResizingEnabled": false, "autoResizeOptions": { "precision": 5, "skipAutoWidth": true } };
    //# sourceURL=smush-lazy-load-js-before
</script>
<script src="{{ asset('wp-content/plugins/wp-smushit/app/assets/js/smush-lazy-load.mina2f3.js') }}"
    id="smush-lazy-load-js"></script>
<script src="{{ asset('wp-content/plugins/elementor-pro/assets/js/webpack-pro.runtime.min7ddb.js') }}" id="elementor-pro-webpack-runtime-js"></script>
<script src="{{ asset('wp-includes/js/dist/hooks.minaf5f.js') }}" id="wp-hooks-js"></script>
<script src="{{ asset('wp-includes/js/dist/i18n.min1cde.js') }}" id="wp-i18n-js"></script>
<script id="wp-i18n-js-after">
    wp.i18n.setLocaleData({ 'text direction\u0004ltr': ['ltr'] });
    //# sourceURL=wp-i18n-js-after
</script>
<script id="elementor-pro-frontend-js-before">
    var ElementorProFrontendConfig = { "ajaxurl": "https:\/\/demokit.creativemox.com\/capwise\/wp-admin\/admin-ajax.php", "nonce": "2b2aed5918", "urls": { "assets": "https:\/\/demokit.creativemox.com\/capwise\/wp-content\/plugins\/elementor-pro\/assets\/", "rest": "https:\/\/demokit.creativemox.com\/capwise\/wp-json\/" }, "settings": { "lazy_load_background_images": true }, "popup": { "hasPopUps": false }, "shareButtonsNetworks": { "facebook": { "title": "Facebook", "has_counter": true }, "twitter": { "title": "Twitter" }, "linkedin": { "title": "LinkedIn", "has_counter": true }, "pinterest": { "title": "Pinterest", "has_counter": true }, "reddit": { "title": "Reddit", "has_counter": true }, "vk": { "title": "VK", "has_counter": true }, "odnoklassniki": { "title": "OK", "has_counter": true }, "tumblr": { "title": "Tumblr" }, "digg": { "title": "Digg" }, "skype": { "title": "Skype" }, "stumbleupon": { "title": "StumbleUpon", "has_counter": true }, "mix": { "title": "Mix" }, "telegram": { "title": "Telegram" }, "pocket": { "title": "Pocket", "has_counter": true }, "xing": { "title": "XING", "has_counter": true }, "whatsapp": { "title": "WhatsApp" }, "email": { "title": "Email" }, "print": { "title": "Print" }, "x-twitter": { "title": "X" }, "threads": { "title": "Threads" } }, "facebook_sdk": { "lang": "en_US", "app_id": "" }, "lottie": { "defaultAnimationUrl": "https:\/\/demokit.creativemox.com\/capwise\/wp-content\/plugins\/elementor-pro\/modules\/lottie\/assets\/animations\/default.json" } };
    //# sourceURL=elementor-pro-frontend-js-before
</script>
<script src="{{ asset('wp-content/plugins/elementor-pro/assets/js/frontend.min7ddb.js') }}" id="elementor-pro-frontend-js"></script>
<script src="{{ asset('wp-content/plugins/elementor-pro/assets/js/elements-handlers.min7ddb.js') }}" id="pro-elements-handlers-js"></script>
<script src="{{ asset('wp-content/plugins/elementskit-lite/widgets/init/assets/js/animate-circle.minac9e.js') }}" id="animate-circle-js"></script>
<script id="elementskit-elementor-js-extra">
    var ekit_config = { "ajaxurl": "https://demokit.creativemox.com/capwise/wp-admin/admin-ajax.php", "nonce": "07beb74fc3" };
    //# sourceURL=elementskit-elementor-js-extra
</script>
<script src="{{ asset('wp-content/plugins/elementskit-lite/widgets/init/assets/js/elementorac9e.js') }}" id="elementskit-elementor-js"></script>
<script id="wp-emoji-settings" type="application/json">
{"baseUrl":"https://s.w.org/images/core/emoji/17.0.2/72x72/","ext":".png","svgUrl":"https://s.w.org/images/core/emoji/17.0.2/svg/","svgExt":".svg","source":{"concatemoji":"https://demokit.creativemox.com/capwise/wp-includes/js/wp-emoji-release.min.js?ver=6.9"}}
</script>
<script type="module">
    /*! This file is auto-generated */
    const a = JSON.parse(document.getElementById("wp-emoji-settings").textContent), o = (window._wpemojiSettings = a, "wpEmojiSettingsSupports"), s = ["flag", "emoji"]; function i(e) { try { var t = { supportTests: e, timestamp: (new Date).valueOf() }; sessionStorage.setItem(o, JSON.stringify(t)) } catch (e) { } } function c(e, t, n) { e.clearRect(0, 0, e.canvas.width, e.canvas.height), e.fillText(t, 0, 0); t = new Uint32Array(e.getImageData(0, 0, e.canvas.width, e.canvas.height).data); e.clearRect(0, 0, e.canvas.width, e.canvas.height), e.fillText(n, 0, 0); const a = new Uint32Array(e.getImageData(0, 0, e.canvas.width, e.canvas.height).data); return t.every((e, t) => e === a[t]) } function p(e, t) { e.clearRect(0, 0, e.canvas.width, e.canvas.height), e.fillText(t, 0, 0); var n = e.getImageData(16, 16, 1, 1); for (let e = 0; e < n.data.length; e++)if (0 !== n.data[e]) return !1; return !0 } function u(e, t, n, a) { switch (t) { case "flag": return n(e, "\ud83c\udff3\ufe0f\u200d\u26a7\ufe0f", "\ud83c\udff3\ufe0f\u200b\u26a7\ufe0f") ? !1 : !n(e, "\ud83c\udde8\ud83c\uddf6", "\ud83c\udde8\u200b\ud83c\uddf6") && !n(e, "\ud83c\udff4\udb40\udc67\udb40\udc62\udb40\udc65\udb40\udc6e\udb40\udc67\udb40\udc7f", "\ud83c\udff4\u200b\udb40\udc67\u200b\udb40\udc62\u200b\udb40\udc65\u200b\udb40\udc6e\u200b\udb40\udc67\u200b\udb40\udc7f"); case "emoji": return !a(e, "\ud83e\u1fac8") }return !1 } function f(e, t, n, a) { let r; const o = (r = "undefined" != typeof WorkerGlobalScope && self instanceof WorkerGlobalScope ? new OffscreenCanvas(300, 150) : document.createElement("canvas")).getContext("2d", { willReadFrequently: !0 }), s = (o.textBaseline = "top", o.font = "600 32px Arial", {}); return e.forEach(e => { s[e] = t(o, e, n, a) }), s } function r(e) { var t = document.createElement("script"); t.src = e, t.defer = !0, document.head.appendChild(t) } a.supports = { everything: !0, everythingExceptFlag: !0 }, new Promise(t => { let n = function () { try { var e = JSON.parse(sessionStorage.getItem(o)); if ("object" == typeof e && "number" == typeof e.timestamp && (new Date).valueOf() < e.timestamp + 604800 && "object" == typeof e.supportTests) return e.supportTests } catch (e) { } return null }(); if (!n) { if ("undefined" != typeof Worker && "undefined" != typeof OffscreenCanvas && "undefined" != typeof URL && URL.createObjectURL && "undefined" != typeof Blob) try { var e = "postMessage(" + f.toString() + "(" + [JSON.stringify(s), u.toString(), c.toString(), p.toString()].join(",") + "));", a = new Blob([e], { type: "text/javascript" }); const r = new Worker(URL.createObjectURL(a), { name: "wpTestEmojiSupports" }); return void (r.onmessage = e => { i(n = e.data), r.terminate(), t(n) }) } catch (e) { } i(n = f(s, u, c, p)) } t(n) }).then(e => { for (const n in e) a.supports[n] = e[n], a.supports.everything = a.supports.everything && a.supports[n], "flag" !== n && (a.supports.everythingExceptFlag = a.supports.everythingExceptFlag && a.supports[n]); var t; a.supports.everythingExceptFlag = a.supports.everythingExceptFlag && !a.supports.flag, a.supports.everything || ((t = a.source || {}).concatemoji ? r(t.concatemoji) : t.wpemoji && t.twemoji && (r(t.twemoji), r(t.wpemoji))) });
    //# sourceURL=https://demokit.creativemox.com/capwise/wp-includes/js/wp-emoji-loader.min.js
</script>