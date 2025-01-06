import { config, mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';

import { Breadcrumb, BreadcrumbItem } from '@/shared/ui/breadcrumb';
import { Models } from '@/types';

import Breadcrumbs from './Breadcrumbs.vue';

const addBreadcrumbs = (...breadcrumb: Models.Breadcrumb[]) => {
    config.global.mocks = {
        $page: {
            props: {
                breadcrumbs: breadcrumb,
            },
        },
    };
};

describe('Breadcrumbs test (Breadcrumbs.vue)', () => {
    it('Will not render breadcrumbs if they are empty', () => {
        addBreadcrumbs();
        const wrapper = mount(Breadcrumbs);

        const breadcrumbs = wrapper.findComponent(Breadcrumb);

        expect(breadcrumbs.exists()).toBeFalsy();
    });

    it('Will render breadcrumbs', () => {
        addBreadcrumbs({ title: 'first' }, { title: 'second' }, { title: 'third' });
        const wrapper = mount(Breadcrumbs);

        const breadcrumbs = wrapper.findAllComponents(BreadcrumbItem);

        expect(breadcrumbs.length).toBe(3);
    });
});
