import { mount } from '@vue/test-utils';
import { describe, expect, it } from 'vitest';

import Checkbox from './Checkbox.vue';

describe('Checkbox test (Checkbox.vue)', () => {
    it('should update model value (boolean)', async () => {
        const wrapper = mount(Checkbox, {
            props: {
                modelValue: false,
            },
        });

        const input = wrapper.find('input');

        expect(input.element.checked).toBeFalsy();

        await input.setValue(true);

        expect(wrapper.emitted()['update:modelValue'][0]).toEqual([true]);
    });

    it('should update model value (array)', async () => {
        const wrapper = mount(Checkbox, {
            props: {
                modelValue: ['item1'],
                value: 'item2',
            },
        });

        const input = wrapper.find('input');

        expect(input.element.checked).toBeFalsy();

        await input.setValue(true);

        expect(wrapper.emitted()['update:modelValue'][0]).toEqual([['item1', 'item2']]);

        await input.setValue(false);

        expect(wrapper.emitted()['update:modelValue'][1]).toEqual([['item1']]);
    });

    it('should render props correctly', () => {
        const wrapper = mount(Checkbox, {
            props: {
                modelValue: false,
                value: 'item1',
            },
        });

        const input = wrapper.find('input');

        expect(input.attributes('type')).toBe('checkbox');

        expect(input.element.value).toBe('item1');
    });
});
