import { describe, expect, it } from 'vitest';
import { mount } from '@vue/test-utils';
import BaseModal from './Modal.vue';

const sizes = ['xs', 'sm', 'md', 'lg', 'xl', '2xl', '3xl', '4xl', '5xl', '6xl', '7xl'];

describe('BaseModal test (Modal.vue)', () => {
    it('Should be visible when the visible prop is true', () => {
        const wrapper = mount(BaseModal, {
            props: { visible: true },
        });

        expect(wrapper.find('.modal-backdrop').isVisible()).toBeTruthy();
        expect(wrapper.find('.modal-wrapper').isVisible()).toBeTruthy();
    });

    it('Should be hidden when the visible prop is false', () => {
        const wrapper = mount(BaseModal, {
            props: { visible: false },
        });

        expect(wrapper.find('.modal-backdrop').exists()).toBeFalsy();
        expect(wrapper.find('.modal-wrapper').exists()).toBeFalsy();
    });

    it.each(sizes)('Modal renders with size %s', (size: string) => {
        const wrapper = mount(BaseModal, {
            props: { visible: true, size },
        });
        expect(wrapper.find('.modal').classes()).toContain(`max-w-${size}`);
    });

    it('Modal will be closed on escape key press if closeOnEscape is true', async () => {
        const wrapper = mount(BaseModal, {
            props: { visible: true, closeOnEscape: true },
        });

        const modal = wrapper.find('.modal-wrapper');
        expect(modal.isVisible()).toBeTruthy();

        const escapeEvent = new KeyboardEvent('keydown', { key: 'Escape' });
        window.dispatchEvent(escapeEvent);

        expect(wrapper.emitted()).toHaveProperty('close');
    });

    it('Modal will be closed on outside click if closeOnOutsideClick is true', async () => {
        const wrapper = mount(BaseModal, {
            props: { visible: true, closeOnOutsideClick: true },
        });

        await wrapper.find('.modal-wrapper').trigger('click');
        expect(wrapper.emitted()).toHaveProperty('click:outside');
        expect(wrapper.emitted()).toHaveProperty('close');
    });

    it('Modal will render content in slots', async () => {
        const wrapper = mount(BaseModal, {
            props: { visible: true },
            slots: {
                header: '<div>Test Header</div>',
                body: '<div>Test Body</div>',
                footer: '<div>Test Footer</div>',
            },
        });

        expect(wrapper.find('header').html()).toContain('<div>Test Header</div>');
        expect(wrapper.find('main').html()).toContain('<div>Test Body</div>');
        expect(wrapper.find('footer').html()).toContain('<div>Test Footer</div>');
    });

    it('Modal will not render close closeButton prop is false', () => {
        const wrapper = mount(BaseModal, {
            props: { visible: true, closeButton: false },
        });

        expect(wrapper.find('button[aria-label="close"]').exists()).toBeFalsy();
    });
});
