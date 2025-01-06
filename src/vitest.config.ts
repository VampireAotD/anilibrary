import { defineConfig, mergeConfig } from 'vite';

import viteConfig from './vite.config';

export default mergeConfig(
    viteConfig,
    defineConfig({
        test: {
            setupFiles: ['resources/js/mocks/ziggy/setup.ts'],
            globals: true,
            environment: 'happy-dom',
        },
    })
);
