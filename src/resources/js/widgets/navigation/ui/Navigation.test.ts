import { config, mount } from '@vue/test-utils';
import { afterAll, describe, expect, it, vi } from 'vitest';

import { usePage } from '@inertiajs/vue3';

import { NavigationLink } from '@/features/navigation/navigation-link';
import { HasRolePlugin } from '@/shared/plugins/user/authorize';
import { Models } from '@/types';

import Navigation from './Navigation.vue';

vi.mock('@inertiajs/vue3', async (importOriginal) => {
    return {
        ...(await importOriginal<typeof import('@inertiajs/vue3')>()),
        usePage: vi.fn(),
    };
});

afterAll(() => vi.clearAllMocks());

const addRole = (role: Models.Role) => {
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

describe('Navigation test (Navigation.vue)', () => {
    config.global.plugins = [HasRolePlugin];

    it('Invitation link must not be rendered if user is not owner', () => {
        addRole({ name: 'admin' });

        const wrapper = mount(Navigation);

        const links = wrapper.findAllComponents(NavigationLink);

        expect(links.length).toBe(4); // Home, anime search, random anime and logout
    });

    it('Owner must see rendered invitation link', () => {
        addRole({ name: 'owner' });

        const wrapper = mount(Navigation);

        const links = wrapper.findAllComponents(NavigationLink);

        const invitationLink = links.find(
            (link) => link.find('a').attributes('title') === 'Invitations'
        );

        expect(invitationLink.exists()).toBeTruthy();
        expect(links.length).toBe(5); // Home, invitations, anime search, random anime and logout
    });
});
