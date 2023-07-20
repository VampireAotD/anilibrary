import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import ziggyRoute, { Config as ZiggyConfig } from 'ziggy-js';
import { PageProps as AppPageProps } from './';
import { hasRole as hasRoleChecker } from "@/plugins/user/authorize";
import { onTelegramAuth } from "@/types/telegram/types";

declare global {
    interface Window {
        axios: AxiosInstance;
        onTelegramAuth: typeof onTelegramAuth
    }

    var route: typeof ziggyRoute;
    var Ziggy: ZiggyConfig;
    var hasRole: typeof hasRoleChecker;
}

declare module 'vue' {
    interface ComponentCustomProperties {
        route: typeof ziggyRoute;
        hasRole: typeof hasRoleChecker;
    }
}

declare module '@inertiajs/core' {
    interface PageProps extends InertiaPageProps, AppPageProps {
    }
}
