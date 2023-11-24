import './bootstrap';
import '../css/app.css';
import 'primevue/resources/themes/lara-dark-indigo/theme.css';
import 'primeicons/primeicons.css';

import { createApp, DefineComponent, h } from 'vue';
import { createInertiaApp } from '@inertiajs/vue3';
import { resolvePageComponent } from 'laravel-vite-plugin/inertia-helpers';
import { ZiggyVue } from 'ziggy-js/dist/vue.m';
import { HasRolePlugin } from '@/plugins/user/authorize';
import PrimeVue from 'primevue/config';
import Tailwind from 'primevue/passthrough/tailwind';
import ToastService from 'primevue/toastservice';
import Toast from 'primevue/toast';

const appName = import.meta.env.VITE_APP_NAME || 'Anilibrary';

createInertiaApp({
    title: (title) => `${title} - ${appName}`,
    resolve: (name) =>
        resolvePageComponent(
            `./Pages/${name}.vue`,
            import.meta.glob<DefineComponent>('./Pages/**/*.vue')
        ),
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .use(plugin)
            .use(ZiggyVue, Ziggy)
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
