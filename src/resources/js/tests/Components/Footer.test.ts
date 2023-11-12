import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import Footer from '@/Components/Footer.vue';

describe('Footer test (Footer.vue)', () => {
    it('Renders correctly', () => {
        const wrapper = mount(Footer, {
            global: {
                mocks: {
                    route: vitest.fn().mockReturnValue('dashboard'),
                },
            },
        });

        expect(wrapper.exists()).toBeTruthy();
        expect(wrapper.find('footer').exists()).toBeTruthy();

        const dashboardLink = wrapper.findComponent({ name: 'Link' });
        expect(dashboardLink.exists()).toBeTruthy();
        expect(dashboardLink.text()).toContain('Anilibrary');
    });
});
