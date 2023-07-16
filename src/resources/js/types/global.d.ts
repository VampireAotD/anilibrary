import { PageProps as InertiaPageProps } from '@inertiajs/core';
import { AxiosInstance } from 'axios';
import ziggyRoute, { Config as ZiggyConfig } from 'ziggy-js';
import { PageProps as AppPageProps } from './';
import { hasRole as hasRoleChecker } from "@/plugins/user/authorize";

declare global {
    interface Window {
        axios: AxiosInstance;
    }

    let route: typeof ziggyRoute;
    let Ziggy: ZiggyConfig;
    let hasRole: typeof hasRoleChecker;
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
