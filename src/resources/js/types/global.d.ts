import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import ziggyRoute, { Config as ZiggyConfig } from 'ziggy-js';
import { PageProps as AppPageProps } from './';
import { useHasRole } from "@/plugins/user/authorize";
import { onTelegramAuth } from "@/types/telegram/types";
import Pusher from 'pusher-js';
import Echo from 'laravel-echo';

declare global {
    interface Window {
        axios: AxiosInstance;
        onTelegramAuth: typeof onTelegramAuth;
        pusher: Pusher;
        echo: Echo;
    }

    var route: typeof ziggyRoute;
    var Ziggy: ZiggyConfig;
    var hasRole: typeof useHasRole;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
        hasRole: typeof useHasRole;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {
    }
}
