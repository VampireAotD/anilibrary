import { describe, expect, it } from 'vitest';
import { mount } from '@vue/test-utils';
import Button from './Button.vue';

describe('Button test (Button.vue)', () => {
    it('Renders properly', () => {
        const wrapper = mount(Button, { slots: { default: '<span>Test</span>' } });
        expect(wrapper.html()).toContain('<span>Test</span>');
    });

    it('Applies variant classes correctly', () => {
        const wrapper = mount(Button, { props: { variant: 'danger' } });
        expect(wrapper.classes()).toContain('bg-red-600');
        expect(wrapper.classes()).toContain('text-white');
    });

    it('Applies size classes correctly', () => {
        const wrapper = mount(Button, { props: { size: 'large' } });
        expect(wrapper.classes()).toContain('px-5');
        expect(wrapper.classes()).toContain('py-3');
        expect(wrapper.classes()).toContain('text-base');
    });

    it('Applies rounded class when rounded prop is true', () => {
        const wrapper = mount(Button, { props: { rounded: true } });
        expect(wrapper.classes()).toContain('rounded-full');
    });

    it('Sets the button type correctly', () => {
        const wrapper = mount(Button, { props: { type: 'submit' } });
        expect(wrapper.attributes('type')).toBe('submit');
    });
});
