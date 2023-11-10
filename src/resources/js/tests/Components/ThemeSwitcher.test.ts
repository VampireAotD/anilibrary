import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';
import ThemeSwitcher from '@/Components/ThemeSwitcher.vue';

describe('ThemeSwitcher test (ThemeSwitcher.vue)', () => {
    it('Toggles dark mode when button is clicked', async () => {
        const wrapper = mount(ThemeSwitcher);

        expect(wrapper.exists()).toBeTruthy();

        // By default regular theme is active
        expect(wrapper.vm.isDark).toBeFalsy();

        await wrapper.find('button').trigger('click');

        // By pressing button dark theme must be active
        expect(wrapper.vm.isDark).toBeTruthy();

        await wrapper.find('button').trigger('click');

        // By clicking again regular theme must be active
        expect(wrapper.vm.isDark).toBeFalsy();
    });
});
