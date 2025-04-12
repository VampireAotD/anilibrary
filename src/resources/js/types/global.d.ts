import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import Echo from 'laravel-echo';
import Pusher from 'pusher-js';
import { RouteParams, Router, route as ziggyRoute } from 'ziggy-js';

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

    function route(): Router;
    function route(
        name: string,
        params?: RouteParams<typeof name> | undefined,
        absolute?: boolean
    ): string;

    const route: typeof ziggyRoute;
    const hasRole: typeof useHasRole;
}

declare module '@vue/runtime-core' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
        hasRole: typeof useHasRole;
    }
}

declare module 'vite/client' {
    interface ImportMetaEnv {
        readonly VITE_APP_NAME: string;

        [key: string]: string | boolean | undefined;
    }

    interface ImportMeta {
        readonly env: ImportMetaEnv;
        readonly glob: <T>(pattern: string) => Record<string, () => Promise<T>>;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {}
}
