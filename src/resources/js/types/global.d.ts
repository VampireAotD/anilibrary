import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import { route as ziggyRoute } from 'ziggy-js';
import { PageProps as AppPageProps } from './';
import { useHasRole } from '@/shared/plugins/user/authorize';
import { onTelegramAuth } from '@/entities/telegram-user';
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

declare global {
    interface Window {
        axios: AxiosInstance;
        onTelegramAuth?: typeof onTelegramAuth;
        pusher: Pusher;
        echo: Echo;
    }

    /*eslint-disable*/
    var route: typeof ziggyRoute;
    var hasRole: typeof useHasRole;
    /*eslint-enable*/
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
