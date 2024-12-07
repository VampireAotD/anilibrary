import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';

import Footer from './Footer.vue';

describe('Footer test (Footer.vue)', () => {
    it('Renders correctly', () => {
        const wrapper = mount(Footer);

        expect(wrapper.exists()).toBeTruthy();
        expect(wrapper.find('footer').exists()).toBeTruthy();

        const dashboardLink = wrapper.findComponent({ name: 'Link' });
        expect(dashboardLink.exists()).toBeTruthy();
        expect(dashboardLink.text()).toContain('Anilibrary');
    });
});
