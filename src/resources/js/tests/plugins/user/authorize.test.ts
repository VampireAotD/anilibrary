import { describe, expect, it, vi } from 'vitest';
import { usePage } from '@inertiajs/vue3';
import { useHasRole } from '@/plugins/user/authorize';

vi.mock('@inertiajs/vue3', () => ({
    ...vi.importActual('@inertiajs/vue3'),
    usePage: vi.fn(),
}));

const mockedUsePage = vi.mocked(usePage);
const mockedData = {
    props: {
        auth: {
            user: {},
        },
    },
};

describe('Authorize plugin test (authorize.ts)', () => {
    it("Should return false if user don't have any roles", () => {
        mockedData.props.auth.user = {
            roles: [],
        };

        mockedUsePage.mockReturnValueOnce({ ...mockedData });

        expect(useHasRole('admin')).toBeFalsy();
    });

    it('Should return false if there is no user', () => {
        mockedUsePage.mockReturnValueOnce({ ...mockedData });

        expect(useHasRole('admin')).toBeFalsy();
    });

    it("Should return false if user don't have required role", () => {
        mockedData.props.auth.user = {
            roles: [
                {
                    name: 'owner',
                },
            ],
        };

        mockedUsePage.mockReturnValueOnce({ ...mockedData });

        expect(useHasRole('admin')).toBeFalsy();
    });

    it('Should return true if user has role', () => {
        mockedData.props.auth.user = {
            roles: [
                {
                    name: 'admin',
                },
            ],
        };

        mockedUsePage.mockReturnValueOnce({ ...mockedData });

        expect(useHasRole('admin')).toBeTruthy();
    });
});
