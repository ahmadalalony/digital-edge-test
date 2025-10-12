import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js',
                'resources/js/users.js',
                'resources/js/products.js',
                'resources/js/notifications.js',
            ],
            refresh: true,
        }),
    ],
});
