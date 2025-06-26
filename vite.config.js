import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/js/app.js'],
            refresh: [
                'resources/views/**',
                'resources/js/**',
                'resources/css/**',
            ],
        }),
    ],
    server: {
        hmr: {
            host: 'localhost',
        },
        host: true,
        watch: {
            usePolling: true,
        },
    },
});
