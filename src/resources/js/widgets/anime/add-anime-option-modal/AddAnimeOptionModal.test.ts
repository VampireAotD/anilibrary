import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it } from 'vitest';

import PrimeVue from 'primevue/config';
import Toast from 'primevue/toast';
import ToastService from 'primevue/toastservice';

import { CreateAnimeModal } from '@/features/anime/create-anime-modal';
import { ScrapeAnimeModal } from '@/features/anime/scrape-anime-modal';

import AddAnimeOptionModal from './AddAnimeOptionModal.vue';

describe('AddAnimeOptionModal test (AddAnimeOptionModal.vue)', () => {
    let wrapper;

    beforeEach(() => {
        wrapper = mount(AddAnimeOptionModal, {
            props: {
                visible: true,
            },
            global: {
                plugins: [PrimeVue, ToastService],
                components: {
                    Toast,
                },
            },
        });
    });

    it('Renders the modal when "visible" is true', () => {
        expect(wrapper.isVisible()).toBeTruthy();
    });

    it('Closes the modal on close event', async () => {
        await wrapper.vm.$emit('close');
        expect(wrapper.emitted()).toHaveProperty('close');
    });

    it('Toggles scrape modal visibility', async () => {
        const button = wrapper.find('.scrape-option');
        expect(button.exists()).toBeTruthy();

        await button.trigger('click');

        expect(wrapper.findComponent(ScrapeAnimeModal).isVisible()).toBeTruthy();
        expect(wrapper.emitted()).toHaveProperty('close');
    });

    it('Toggles create modal visibility', async () => {
        const button = wrapper.find('.create-option');

        await button.trigger('click');

        expect(wrapper.findComponent(CreateAnimeModal).isVisible()).toBeTruthy();
        expect(wrapper.emitted()).toHaveProperty('close');
    });
});
