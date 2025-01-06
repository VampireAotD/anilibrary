import { mount } from '@vue/test-utils';
import { beforeEach, describe, expect, it } from 'vitest';

import { CreateAnimeForm } from '@/features/anime/create-anime-form';
import { ScrapeAnimeForm } from '@/features/anime/scrape-anime-form';
import { Modal } from '@/shared/ui/modal';
import { Toaster } from '@/shared/ui/toast';

import AddAnimeOptionModal from './AddAnimeModal.vue';

describe('AddAnimeModal test (AddAnimeModal.vue)', () => {
    let wrapper;

    beforeEach(() => {
        wrapper = mount(AddAnimeOptionModal, {
            props: {
                visible: true,
            },
            global: {
                stubs: {
                    teleport: true,
                },
                components: {
                    Toaster,
                },
            },
        });
    });

    it('Renders the modal when "visible" is true', () => {
        expect(wrapper.findComponent(Modal).exists()).toBeTruthy();
        expect(wrapper.findComponent(Modal).props('visible')).toBeTruthy();
    });

    it('Emits close event when Modal emits close', async () => {
        await wrapper.findComponent(Modal).vm.$emit('close');
        expect(wrapper.emitted()).toHaveProperty('close');
    });

    it('Shows options and hides forms initially', () => {
        const optionsDiv = wrapper.find('.scrape-options');
        expect(optionsDiv.exists()).toBeTruthy();
        expect(optionsDiv.isVisible()).toBeTruthy();

        expect(wrapper.findComponent(ScrapeAnimeForm).exists()).toBeFalsy();
        expect(wrapper.findComponent(CreateAnimeForm).exists()).toBeFalsy();
    });

    it('Toggles scrape form visibility', async () => {
        const scrapeButton = wrapper.find('.scrape-option');
        expect(scrapeButton.exists()).toBeTruthy();

        await scrapeButton.trigger('click');

        expect(wrapper.find('.inline-flex').isVisible()).toBeTruthy();
        expect(wrapper.findComponent(ScrapeAnimeForm).exists()).toBeTruthy();
        expect(wrapper.findComponent(CreateAnimeForm).exists()).toBeFalsy();
    });

    it('Toggles create form visibility', async () => {
        const createButton = wrapper.find('.create-option');
        expect(createButton.exists()).toBeTruthy();

        await createButton.trigger('click');

        expect(wrapper.findComponent(ScrapeAnimeForm).exists()).toBeFalsy();
        expect(wrapper.findComponent(CreateAnimeForm).exists()).toBeTruthy();
    });

    it('Closes modal when form emits added event', async () => {
        await wrapper.find('.scrape-option').trigger('click');
        expect(wrapper.findComponent(ScrapeAnimeForm).exists()).toBeTruthy();

        await wrapper.findComponent(ScrapeAnimeForm).vm.$emit('added');

        expect(wrapper.emitted()).toHaveProperty('close');

        expect(wrapper.findComponent(ScrapeAnimeForm).exists()).toBeFalsy();
        expect(wrapper.findComponent(CreateAnimeForm).exists()).toBeFalsy();
    });

    it('Closes create form when cancel is emitted', async () => {
        await wrapper.find('.create-option').trigger('click');
        expect(wrapper.findComponent(CreateAnimeForm).exists()).toBeTruthy();

        await wrapper.findComponent(CreateAnimeForm).vm.$emit('cancel');

        expect(wrapper.emitted()).toHaveProperty('close');
        expect(wrapper.findComponent(CreateAnimeForm).exists()).toBeFalsy();
    });
});
