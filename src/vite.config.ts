/// <reference types="vitest" />

import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import vue from '@vitejs/plugin-vue';
import { configDefaults } from 'vitest/config';

export default defineConfig({
    server: {
        https: false,
        open: false,
    },
    test: {
        globals: true,
        environment: 'happy-dom',
        exclude: [
            ...configDefaults.exclude,
            // Excluded, cause locally works fine, but in CI failing due to Ziggy is located in vendor folder
            'resources/js/tests/Layouts/AuthenticatedLayout.test.ts',
        ],
    },
    plugins: [
        laravel({
            input: 'resources/js/app.ts',
            refresh: true,
        }),
        vue({
            template: {
                transformAssetUrls: {
                    base: null,
                    includeAbsolute: false,
                },
            },
        }),
    ],
});
