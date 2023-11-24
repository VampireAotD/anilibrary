import { afterAll, afterEach, describe, expect, it, vi } from 'vitest';
import { config, mount } from '@vue/test-utils';
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { ZiggyVue } from 'ziggy-js/dist/vue.m';
import { HasRolePlugin } from '@/plugins/user/authorize';
import { usePage } from '@inertiajs/vue3';
import NavLink from '@/Components/NavLink.vue';
import { ZiggyMockConfig } from '../mocks/ziggy-js';

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

        const links = layoutWrapper.findAllComponents(NavLink);
        expect(links.length).toBe(2);
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

        const links = layoutWrapper.findAllComponents(NavLink);
        const invitationLink = links.filter((link) => link.text().match('Invite')).at(0);

        expect(links.length).toBe(3);
        expect(invitationLink.exists()).toBeTruthy();
        expect(invitationLink.text()).toContain('Invite');
    });
});
