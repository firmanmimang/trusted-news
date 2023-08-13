import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';

export default defineConfig({
    plugins: [
        laravel({
            input: ['resources/css/app.css', 'resources/css/cms.css', 'resources/js/entry/frontend/app.js', 'resources/js/entry/cms/app.js'],
            refresh: true,
        }),
    ],
});
