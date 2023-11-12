import { mount } from '@vue/test-utils';
import { describe, expect, it, vitest } from 'vitest';
import TelegramLoginWidget from '../../Components/TelegramLoginWidget.vue';
import { RequestAccess, WidgetSize } from '@/types/telegram/enums';

describe('TelegramLoginWidget test (TelegramLoginWidget.vue)', () => {
    it('Renders correctly', async () => {
        const wrapper = mount(TelegramLoginWidget);
        expect(wrapper.exists()).toBeTruthy();

        const div = wrapper.find({ ref: 'telegramWidget' });
        expect(div.exists()).toBeTruthy();

        await wrapper.vm.$nextTick();

        const script = div.element.querySelector('script');
        expect(script).toBeTruthy();
        expect(script!.hasAttribute('async')).toBeTruthy();
    });

    it('Creates login widget with default props', async () => {
        const wrapper = mount(TelegramLoginWidget);

        const div = wrapper.find({ ref: 'telegramWidget' }).element;

        await wrapper.vm.$nextTick();

        const script = div.querySelector('script');
        expect(script!.getAttribute('data-telegram-login')).toBe('anilibrary_bot');
        expect(script!.getAttribute('data-size')).toBe(WidgetSize.Medium);
        expect(script!.getAttribute('data-radius')).toBe('14');
        expect(script!.getAttribute('data-userpic')).toBe('false');
        expect(script!.getAttribute('data-request-access')).toBe(RequestAccess.Write);
    });

    it('Creates login widget with redirect props', async () => {
        const url = '/redirect-url';
        global.route = vitest.fn().mockReturnValue(url);

        const wrapper = mount(TelegramLoginWidget, {
            props: {
                type: 'redirect',
                redirectUrl: url,
            },
        });

        const div = wrapper.find({ ref: 'telegramWidget' }).element;

        await wrapper.vm.$nextTick();

        const script = div.querySelector('script');
        expect(script!.getAttribute('data-auth-url')).toBe(url);
        expect(script!.hasAttribute('data-onauth')).toBeFalsy();
    });

    it('Creates login widget with callback props', async () => {
        const callbackMock = vitest.fn();
        window.onTelegramAuth = callbackMock;

        const wrapper = mount(TelegramLoginWidget, {
            props: {
                type: 'callback',
                callback: callbackMock,
            },
        });

        const div = wrapper.find({ ref: 'telegramWidget' }).element;

        await wrapper.vm.$nextTick();

        const script = div.querySelector('script');
        expect(script!.getAttribute('data-onauth')).toBe('onTelegramAuth(user)');
        expect(script!.hasAttribute('data-auth-url')).toBeFalsy();
    });
});
