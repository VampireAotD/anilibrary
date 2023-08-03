import { Plugin } from 'vue';
import { Role } from '@/types';
import { usePage } from '@inertiajs/vue3';

const useHasRole = (name: string): boolean => {
    const page = usePage();
    return page.props.auth?.user?.roles?.some((role: Role) => role.name === name);
};

const HasRolePlugin: Plugin = {
    install(app) {
        app.config.globalProperties.hasRole = useHasRole;
        app.provide('hasRole', useHasRole);
    },
};

export { HasRolePlugin, useHasRole };
