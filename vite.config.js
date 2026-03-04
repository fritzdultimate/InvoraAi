import {
    defineConfig
} from 'vite';
import laravel from 'laravel-vite-plugin';
import tailwindcss from "@tailwindcss/vite";

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css', 
                'resources/js/app.js', 
                'resources/css/deposit.css', 
                'resources/css/investment.css',
                'resources/css/invora-ui.css',
                'resources/css/investment-item.css',
                'resources/css/profile.css',
                'resources/css/settings.css',
                'resources/css/referral-overview.css',
                'resources/css/referral-bonus.css',
                'resources/css/referral-direct.css',
            ],
            refresh: true,
        }),
        tailwindcss(),
    ],
    server: {
        cors: true,
        watch: {
            ignored: ['**/storage/framework/views/**'],
        },
    },
});
