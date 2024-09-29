import { Plugin } from 'vue';

import { usePage } from '@inertiajs/vue3';

import { Models } from '@/types';

const useHasRole = (name: string): boolean => {
    const page = usePage();
    return page.props.auth?.user?.roles?.some((role: Models.Role) => role.name === name);
};

const HasRolePlugin: Plugin = {
    install(app) {
        app.config.globalProperties.hasRole = useHasRole;
        app.provide('hasRole', useHasRole);
    },
};

export { HasRolePlugin, useHasRole };
