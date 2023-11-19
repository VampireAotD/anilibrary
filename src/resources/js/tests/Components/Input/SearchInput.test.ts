import { mount } from '@vue/test-utils';
import SearchInput from '@/Components/Input/SearchInput.vue';
import { describe, expect, it } from 'vitest';

describe('SearchInput test (SearchInput.vue)', () => {
    it('renders input element', () => {
        const wrapper = mount(SearchInput);
        const input = wrapper.find('input');

        expect(input.exists()).toBeTruthy();
    });

    it('focuses on input when CTRL + K is pressed', async () => {
        const wrapper = mount(SearchInput, { attachTo: document.body });
        const input = wrapper.find('input');

        const ctrlKEvent = new KeyboardEvent('keydown', { ctrlKey: true, key: 'k' });
        window.dispatchEvent(ctrlKEvent);

        await wrapper.vm.$nextTick();

        expect(input.element).toBe(document.activeElement);
    });

    it('updates query when input value changes', async () => {
        const wrapper = mount(SearchInput);
        const input = wrapper.find('input');

        await input.setValue('test query');

        expect(wrapper.vm.query).toBe('test query');
    });
});
