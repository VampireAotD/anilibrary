import { App, Plugin } from 'vue';
import { Role } from "@/types";

export function hasRole(name: string): boolean {
    return this.$page.props.auth?.user?.roles?.find((role: Role) => role.name === name)
}

export const HasRolePlugin: Plugin = {
    install(app: App) {
        app.config.globalProperties.hasRole = hasRole
        app.provide('hasRole', hasRole)
    }
}
