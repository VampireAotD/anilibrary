import { DefineComponent, createApp, h } from 'vue';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js';

import ripple from '@/shared/directives/ripple';
import { HasRolePlugin } from '@/shared/plugins/user/authorize';

import '../css/app.css';
import './bootstrap';

const appName = import.meta.env.VITE_APP_NAME || 'Anilibrary';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue)
            .use(HasRolePlugin)
            .directive('ripple', ripple)
            .mount(el);
    },
    progress: {
        color: 'hsl(var(--border))',
    },
});
