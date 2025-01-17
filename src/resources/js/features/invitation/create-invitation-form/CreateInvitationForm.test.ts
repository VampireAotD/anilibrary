import { mount } from '@vue/test-utils';
import { describe, expect, it, vitest } from 'vitest';

import InvitationForm from './CreateInvitationForm.vue';

describe('InvitationForm test (CreateInvitationForm.vue)', () => {
    it('Renders correctly', () => {
        const wrapper = mount(InvitationForm);

        expect(wrapper.find('form').exists()).toBeTruthy();
        expect(wrapper.find('label').exists()).toBeTruthy();
        expect(wrapper.find('input').exists()).toBeTruthy();
        expect(wrapper.find('button').exists()).toBeTruthy();
    });

    it('Creates invitation', async () => {
        global.route = vitest.fn();
        const wrapper = mount(InvitationForm);

        wrapper.vm.form.post = vitest.fn();
        wrapper.vm.form.reset = vitest.fn();
        wrapper.vm.form.email = 'test@example.com';
        await wrapper.find('form').trigger('submit.prevent');

        expect(wrapper.vm.form.post).toHaveBeenCalled();
    });

    it('Disables button during form processing', async () => {
        const wrapper = mount(InvitationForm);

        wrapper.vm.form.processing = true;
        await wrapper.vm.$nextTick();

        expect(wrapper.find('button').element.disabled).toBeTruthy();
    });
});
