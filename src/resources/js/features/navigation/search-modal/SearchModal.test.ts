import { describe, expect, it } from 'vitest';
import { mount } from '@vue/test-utils';
import { Modal } from '@/shared/ui/modal';
import { TextInput } from '@/shared/ui/input';
import SearchModal from './SearchModal.vue';

describe('SearchModal test (SearchModal.vue)', () => {
    it('Shows modal when visible is true', () => {
        const wrapper = mount(SearchModal, {
            props: { visible: true },
        });

        expect(wrapper.findComponent(Modal).props('visible')).toBeTruthy();
    });

    it('Modal emits closed event when it is closed', async () => {
        const wrapper = mount(SearchModal, {
            props: { visible: true },
        });

        await wrapper.findComponent(Modal).vm.$emit('close');
        expect(wrapper.emitted()).toHaveProperty('closed');
    });

    it('Modal binds input value to ref', async () => {
        const wrapper = mount(SearchModal, {
            props: { visible: true },
        });

        const input = wrapper.findComponent(TextInput);
        await input.setValue('test query');
        expect(wrapper.vm.query).toBe('test query');
    });
});
