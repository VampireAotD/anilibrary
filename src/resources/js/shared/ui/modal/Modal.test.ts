import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';

import Modal from './Modal.vue';

const sizes = ['xs', 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'];

describe('Modal test (Modal.vue)', () => {
    const createWrapper = (props = {}, slots = {}) => {
        return mount(Modal, {
            props: {
                visible: true,
                ...props,
            },
            slots,
        });
    };

    it('Should be visible when the visible prop is true', async () => {
        const wrapper = createWrapper();

        await wrapper.vm.$nextTick();
        const dialog = wrapper.find('dialog');
        expect(dialog.exists()).toBeTruthy();
        expect(dialog.element.open).toBeTruthy();
    });

    it('Should be hidden when the visible prop is false', async () => {
        const wrapper = createWrapper({ visible: false });

        await wrapper.vm.$nextTick();
        const dialog = wrapper.find('dialog');
        expect(dialog.exists()).toBeTruthy();
        expect(dialog.element.open).toBeFalsy();
    });

    it.each(sizes)('Modal renders with size %s', (size: string) => {
        const wrapper = createWrapper({ size });

        const content = wrapper.find('.modal-content');
        expect(content.exists()).toBeTruthy();
        expect(content.classes()).toContain(`max-w-${size}`);
    });

    it('Modal will be closed on escape key press if closeOnEscape is true', async () => {
        const wrapper = createWrapper({ closeOnEscape: true });

        const modal = wrapper.find('.modal-wrapper');
        expect(modal.isVisible()).toBeTruthy();

        const escapeEvent = new KeyboardEvent('keydown', { key: 'Escape' });
        window.dispatchEvent(escapeEvent);

        expect(wrapper.emitted()).toHaveProperty('close');
    });

    it('Modal will be closed on outside click if closeOnOutsideClick is true', async () => {
        const wrapper = createWrapper({ closeOnOutsideClick: true });

        await wrapper.find('.modal-wrapper').trigger('click');
        expect(wrapper.emitted()).toHaveProperty('click:outside');
        expect(wrapper.emitted()).toHaveProperty('close');
    });

    it('Modal will render content in slots', async () => {
        const wrapper = createWrapper(
            {},
            {
                header: '<div>Test Header</div>',
                body: '<div>Test Body</div>',
                footer: '<div>Test Footer</div>',
            }
        );

        expect(wrapper.find('header').html()).toContain('<div>Test Header</div>');
        expect(wrapper.find('main').html()).toContain('<div>Test Body</div>');
        expect(wrapper.find('footer').html()).toContain('<div>Test Footer</div>');
    });

    it('Modal will not render close closeButton prop is false', () => {
        const wrapper = createWrapper({ closeButton: false });

        expect(wrapper.find('button[aria-label="close"]').exists()).toBeFalsy();
    });
});
