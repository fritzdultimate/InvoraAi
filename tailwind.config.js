import defaultTheme from 'tailwindcss/defaultTheme';
import forms from '@tailwindcss/forms';

/** @type {import('tailwindcss').Config} */
export default {
    darkMode: 'class',
    safelist: [
        'halpha-w-sidebar-expanded',
        'halpha-translate-x-0',
        'halpha--translate-x-full',
        'halpha-bg-usdt',
        'halpha-bg-eth',
        'halpha-bg-xrp',
        'halpha-bg-btc',
        'halpha-bg-ltc',
        'halpha-bg-trx',
    ],
    content: [
        './vendor/laravel/framework/src/Illuminate/Pagination/resources/views/*.blade.php',
        './storage/framework/views/*.php',
        './resources/views/**/*.blade.php',
    ],
    prefix: 'invora-',

    theme: {
        extend: {
            colors: {
                'btc': '#F7931A',
                'eth': '#282828',
                'usdt': '#2ca07a',
                'xrp': '#346aa9',
                'ltc': '#838383',
                'trx': '#c62734'

            },
            spacing: {
                'sidebar-collapsed': '5rem', // 80px
                'sidebar-expanded': '17rem'
            },
            fontFamily: {
                sans: ['"Public Sans"', 'Figtree', ...defaultTheme.fontFamily.sans],
            },
        },
    },

    plugins: [forms, require('tailwind-scrollbar'),],
};
