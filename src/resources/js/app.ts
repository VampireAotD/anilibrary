import { DefineComponent, createApp, h } from 'vue';

import PrimeVue from 'primevue/config';
import Tailwind from 'primevue/passthrough/tailwind';
import 'primevue/resources/themes/lara-dark-indigo/theme.css';
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';

import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import 'primeicons/primeicons.css';
import { ZiggyVue } from 'ziggy-js';

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
            .use(PrimeVue, {
                ripple: true,
                pt: Tailwind,
                ptOptions: { mergeProps: false },
            })
            .use(ToastService)
            .component('Toast', Toast)
            .mount(el);
    },
    progress: {
        color: '#4B5563',
    },
});
