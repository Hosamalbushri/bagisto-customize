import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: true,
        }),
    ],
    define: {
        'import.meta.env.VITE_FIREBASE_VAPID_KEY': JSON.stringify(process.env.FIREBASE_VAPID_KEY || 'YOUR_VAPID_KEY_FROM_FIREBASE_CONSOLE'),
    },
});
