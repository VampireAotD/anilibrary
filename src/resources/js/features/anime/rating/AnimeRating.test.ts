import { beforeEach, describe, expect, it } from 'vitest';
import { mount } from '@vue/test-utils';
import AnimeRating from './AnimeRating.vue';
import Rating from 'primevue/rating';
import PrimeVue from 'primevue/config';

describe('AnimeRating test (AnimeRating.vue)', () => {
    let wrapper;

    beforeEach(() => {
        wrapper = mount(AnimeRating, {
            props: { modelValue: 5 },
            global: {
                plugins: [PrimeVue],
            },
        });
    });

    it('Will display initial rating', () => {
        expect(wrapper.text()).toContain('5 / 10');
        const ratingComponent = wrapper.findComponent(Rating);

        expect(ratingComponent.props('modelValue')).toBe(5);
    });

    it('Will update rating when the modelValue prop changes', async () => {
        await wrapper.setProps({ modelValue: 8 });
        expect(wrapper.text()).toContain('8 / 10');
    });

    it('Has appropriate ARIA attributes for accessibility', () => {
        expect(wrapper.attributes('role')).toBe('status');
        expect(wrapper.attributes('aria-live')).toBe('polite');

        const ratingComponent = wrapper.findComponent(Rating);
        expect(ratingComponent.exists()).toBeTruthy();
    });
});