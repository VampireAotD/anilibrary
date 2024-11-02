import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';

import RippleDirective from './ripple';

describe('Ripple directive test (ripple.ts)', () => {
    const createDirectiveWrapper = (bindingValue = {}) => {
        return mount({
            template: `
                <div v-ripple="options" style="width: 100px; height: 100px;"></div>`,
            data() {
                return { options: bindingValue };
            },
            directives: {
                ripple: RippleDirective,
            },
        });
    };

    it('Will apply default classes when no options are provided', async () => {
        const wrapper = createDirectiveWrapper();
        await wrapper.trigger('click', {
            clientX: 50,
            clientY: 50,
        });

        const rippleElement = wrapper.element.querySelector('span');
        expect(rippleElement).toBeTruthy();
        expect(rippleElement.classList.contains('animate-ripple')).toBeTruthy();
        expect(rippleElement.classList.contains('bg-foreground')).toBeTruthy();
        expect(rippleElement.classList.contains('bg-opacity-25')).toBeTruthy();
    });

    it('Will apply provided color and opacity classes', async () => {
        const wrapper = createDirectiveWrapper({
            color: 'bg-blue-500',
            opacity: 'bg-opacity-50',
        });

        await wrapper.trigger('click', {
            clientX: 50,
            clientY: 50,
        });

        const rippleElement = wrapper.element.querySelector('span');
        expect(rippleElement).toBeTruthy();
        expect(rippleElement.classList.contains('animate-ripple')).toBeTruthy();
        expect(rippleElement.classList.contains('bg-blue-500')).toBeTruthy();
        expect(rippleElement.classList.contains('bg-opacity-50')).toBeTruthy();
    });

    it('Will position the ripple correctly', async () => {
        const wrapper = createDirectiveWrapper();
        const rect = wrapper.element.getBoundingClientRect();
        const clickX = rect.left + rect.width / 2;
        const clickY = rect.top + rect.height / 2;

        await wrapper.trigger('click', {
            clientX: clickX,
            clientY: clickY,
        });

        const rippleElement = wrapper.element.querySelector('span');
        expect(rippleElement).toBeTruthy();
        expect(rippleElement.style.left).toBe(`${clickX - rect.left - rect.width / 2}px`);
        expect(rippleElement.style.top).toBe(`${clickY - rect.top - rect.height / 2}px`);
    });

    it('Will remove ripple after animation ends', async () => {
        const wrapper = createDirectiveWrapper();
        await wrapper.trigger('click', {
            clientX: 50,
            clientY: 50,
        });

        let rippleElement = wrapper.element.querySelector('span');
        expect(rippleElement).toBeTruthy();

        rippleElement.dispatchEvent(new Event('animationend'));
        rippleElement = wrapper.element.querySelector('span');
        expect(rippleElement).toBeFalsy();
    });
});
