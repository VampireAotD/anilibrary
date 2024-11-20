import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';

import Button from './Button.vue';

describe('Button test (Button.vue)', () => {
    it('Renders properly', () => {
        const wrapper = mount(Button, { slots: { default: '<span>Test</span>' } });
        expect(wrapper.html()).toContain('<span>Test</span>');
    });

    it('Applies variant classes correctly', () => {
        const wrapper = mount(Button, { props: { variant: 'destructive' } });
        expect(wrapper.classes()).toContain('bg-destructive');
        expect(wrapper.classes()).toContain('text-destructive-foreground');
        expect(wrapper.classes()).toContain('hover:bg-destructive/90');
    });

    it('Applies size classes correctly', () => {
        const wrapper = mount(Button, { props: { size: 'lg' } });
        expect(wrapper.classes()).toContain('h-11');
        expect(wrapper.classes()).toContain('rounded-md');
        expect(wrapper.classes()).toContain('px-8');
    });

    it('Applies default classes when no props are provided', () => {
        const wrapper = mount(Button);
        expect(wrapper.classes()).toContain('bg-primary');
        expect(wrapper.classes()).toContain('text-primary-foreground');
        expect(wrapper.classes()).toContain('h-10');
        expect(wrapper.classes()).toContain('px-4');
        expect(wrapper.classes()).toContain('py-2');
    });

    it('Combines custom class with variant classes', () => {
        const customClass = 'custom-class';
        const wrapper = mount(Button, {
            props: {
                variant: 'outline',
                class: customClass,
            },
        });
        expect(wrapper.classes()).toContain('border');
        expect(wrapper.classes()).toContain('border-border');
        expect(wrapper.classes()).toContain(customClass);
    });
});
