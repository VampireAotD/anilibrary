import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';

import Rating from './Rating.vue';

describe('Rating test (Rating.vue)', () => {
    const createWrapper = (props = {}, options = {}) => {
        return mount(Rating, {
            props: {
                maxRating: 5,
                modelValue: 0,
                ...props,
            },
            global: {
                stubs: {
                    Star: true,
                },
            },
            ...options,
        });
    };

    it('Renders the correct number of stars', () => {
        const wrapper = createWrapper({ maxRating: 8 });
        expect(wrapper.findAll('.relative').length).toBe(8);
    });

    it('Sets the initial rating correctly', async () => {
        const wrapper = createWrapper({ modelValue: 3 });
        const filledStars = wrapper.findAll('.absolute');
        expect(filledStars[2].attributes('style')).toContain('width: 100%');
        expect(filledStars[3].attributes('style')).toContain('width: 0%');
    });

    it('Updates rating on click', async () => {
        const wrapper = createWrapper();
        await wrapper.findAll('.relative')[2].trigger('click');
        expect(wrapper.emitted('update:modelValue')?.[0]).toEqual([3]);
    });

    it('Handles precision correctly', async () => {
        const wrapper = createWrapper({ precision: 0.5 });

        await wrapper.vm.handleHover(
            {
                offsetX: 15,
                currentTarget: { offsetWidth: 30 },
            },
            3
        );
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.hoverRating).toBe(2.5);

        await wrapper.vm.handleHover(
            {
                offsetX: 25,
                currentTarget: { offsetWidth: 30 },
            },
            3
        );
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.hoverRating).toBe(3);

        await wrapper.setProps({ precision: 0.1 });
        await wrapper.vm.handleHover(
            {
                offsetX: 13,
                currentTarget: { offsetWidth: 30 },
            },
            3
        );
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.hoverRating).toBe(2.5);

        await wrapper.vm.handleHover(
            {
                offsetX: 17,
                currentTarget: { offsetWidth: 30 },
            },
            3
        );
        await wrapper.vm.$nextTick();
        expect(wrapper.vm.hoverRating).toBe(2.6);
    });

    it('Handles hover correctly', async () => {
        const wrapper = createWrapper();
        await wrapper.vm.handleHover(
            {
                offsetX: 15,
                currentTarget: { offsetWidth: 30 },
            },
            3
        );
        await wrapper.vm.$nextTick();

        const filledStars = wrapper.findAll('.absolute');
        expect(filledStars[2].attributes('style')).toContain('width: 100%');
        expect(filledStars[3].attributes('style')).toContain('width: 0%');
        expect(wrapper.vm.hoverRating).toBe(3);
    });

    it('Respects readonly prop', async () => {
        const wrapper = createWrapper({ readonly: true });
        await wrapper.findAll('.relative')[2].trigger('click');
        expect(wrapper.emitted('update:modelValue')).toBeFalsy();
    });

    it('Uses custom icons when provided', () => {
        const wrapper = createWrapper(
            {},
            {
                slots: {
                    'empty-icon': '<span class="empty-custom-icon"></span>',
                    'filled-icon': '<span class="filled-custom-icon"></span>',
                },
            }
        );

        expect(wrapper.find('.empty-custom-icon').exists()).toBeTruthy();
        expect(wrapper.find('.filled-custom-icon').exists()).toBeTruthy();
    });
});
