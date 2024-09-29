import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { route as ziggyRoute } from 'ziggy-js';

import { onTelegramAuth } from '@/entities/telegram-user';
import { useHasRole } from '@/shared/plugins/user/authorize';

import { PageProps as AppPageProps } from './';

declare global {
    interface Window {
        axios: AxiosInstance;
        onTelegramAuth?: typeof onTelegramAuth;
        pusher: Pusher;
        echo: Echo;
    }

    const route: typeof ziggyRoute;
    const hasRole: typeof useHasRole;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
        hasRole: typeof useHasRole;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}
