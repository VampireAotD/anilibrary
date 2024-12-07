import { config, mount } from '@vue/test-utils';
import { afterAll, describe, expect, it, vi } from 'vitest';

import { usePage } from '@inertiajs/vue3';

import { NavigationLink } from '@/features/navigation/navigation-link';
import Ripple from '@/shared/directives/ripple';
import { HasRolePlugin } from '@/shared/plugins/user/authorize';

import AuthenticatedLayout from './AuthenticatedLayout.vue';

vi.mock('@inertiajs/vue3', async () => {
    const actual =
        await vi.importActual<typeof import('@inertiajs/vue3')>('@inertiajs/vue3');
    return {
        ...actual,
        usePage: vi.fn(),
    };
});

const addRole = (role: object) => {
    vi.mocked(usePage).mockReturnValue({
        props: {
            auth: {
                user: {
                    roles: [role],
                },
            },
        },
    });
};

afterAll(() => vi.clearAllMocks());

describe('AuthenticatedLayout test (AuthenticatedLayout.vue)', () => {
    config.global.directives = { ripple: Ripple };
    config.global.plugins = [HasRolePlugin];

    it('Invitation link must not be rendered if user is not owner', () => {
        addRole({ name: 'admin' });

        const wrapper = mount(AuthenticatedLayout);

        const links = wrapper.findAllComponents(NavigationLink);

        expect(links.length).toBe(4); // Home, anime search, random anime and logout
    });

    it('Owner must see rendered invitation link', () => {
        addRole({ name: 'owner' });

        const wrapper = mount(AuthenticatedLayout);

        const links = wrapper.findAllComponents(NavigationLink);

        const invitationLink = links.find(
            (link) => link.find('a').attributes('title') === 'Invitations'
        );

        expect(invitationLink.exists()).toBeTruthy();
        expect(links.length).toBe(5); // Home, invitations, anime search, random anime and logout
    });
});
