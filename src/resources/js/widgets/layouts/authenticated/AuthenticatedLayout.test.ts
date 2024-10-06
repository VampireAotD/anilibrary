import { config, mount } from '@vue/test-utils';
import { afterAll, afterEach, describe, expect, it, vi } from 'vitest';

import { usePage } from '@inertiajs/vue3';
import { ZiggyVue } from 'ziggy-js';

import { NavigationLink } from '@/features/navigation/navigation-link';
import { ZiggyMockConfig } from '@/mocks/ziggy-js';
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

const mockedUsePage = vi.mocked(usePage);

const mockedData = {
    props: {
        auth: {
            user: {
                roles: [],
            },
        },
    },
};

afterEach(() => {
    mockedData.props.auth.user.roles = [];
});

afterAll(() => {
    vi.clearAllMocks();
});

describe('AuthenticatedLayout test (AuthenticatedLayout.vue)', () => {
    config.global.plugins = [[ZiggyVue, ZiggyMockConfig], [HasRolePlugin]];

    it('Invitation link must not be rendered if user is not owner', () => {
        mockedData.props.auth.user.roles.push({ name: 'admin' });
        mockedUsePage.mockReturnValue(mockedData);

        const layoutWrapper = mount(AuthenticatedLayout, {
            global: {
                mocks: {
                    $page: mockedData,
                },
            },
        });

        const links = layoutWrapper.findAllComponents(NavigationLink);
        expect(links.length).toBe(4); // Home, anime list, random anime and logout
    });

    it('Owner must see rendered invitation link', () => {
        mockedData.props.auth.user.roles.push({ name: 'owner' });
        mockedUsePage.mockReturnValue(mockedData);

        const layoutWrapper = mount(AuthenticatedLayout, {
            global: {
                mocks: {
                    $page: mockedData,
                },
            },
        });

        const links = layoutWrapper.findAllComponents(NavigationLink);
        const invitationLink = links.find(
            (link) => link.find('a').attributes('title') === 'Invitation'
        );

        expect(links.length).toBe(5); // Home, invitation, anime list, random anime and logout
        expect(invitationLink.exists()).toBeTruthy();
    });
});
