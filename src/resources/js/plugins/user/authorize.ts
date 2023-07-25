import { Plugin } from 'vue';
import { Role } from "@/types";

function useHasRole(name: string) {
    return this.$page.props.auth?.user?.roles?.find((role: Role) => role.name === name)
}

const HasRolePlugin: Plugin = {
    install(app) {
        app.config.globalProperties.hasRole = useHasRole
        app.provide('hasRole', useHasRole)
    }
}

export { HasRolePlugin, useHasRole }
