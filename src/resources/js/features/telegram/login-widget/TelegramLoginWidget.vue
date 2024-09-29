<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';

import { type TelegramUser } from '@/entities/telegram-user';
import { RequestAccess, WidgetSize } from '@/features/telegram/login-widget/model/types';

type Props = {
    bot: string;
    size: WidgetSize;
    radius: number;
    showUserPicture: boolean;
    accessType: RequestAccess;
};

type WithRedirect = Props & {
    type: 'redirect';
    redirectUrl: string;
};

type WithCallback = Props & {
    type: 'callback';
    callback: (user: TelegramUser) => void;
};

const widgetUrl = 'https://telegram.org/js/telegram-widget.js?22';
const telegramWidget = ref<HTMLElement | null>(null);
const props = withDefaults(defineProps<Partial<WithRedirect | WithCallback>>(), {
    bot: 'anilibrary_bot',
    size: WidgetSize.Medium,
    radius: 14,
    showUserPicture: false,
    accessType: RequestAccess.Write,
});

const createLoginWidget = (): HTMLElement => {
    const script = document.createElement('script');
    script.async = true;
    script.src = widgetUrl;

    script.setAttribute('data-telegram-login', props.bot);
    script.setAttribute('data-size', props.size.toString());
    script.setAttribute('data-radius', props.radius.toString());
    script.setAttribute('data-userpic', props.showUserPicture.toString());
    script.setAttribute('data-request-access', props.accessType);

    if (props.type === 'redirect') {
        script.setAttribute('data-auth-url', route((props as WithRedirect).redirectUrl));

        return script;
    }

    window.onTelegramAuth = (props as WithCallback).callback;
    script.setAttribute('data-onauth', 'onTelegramAuth(user)');

    return script;
};

onMounted(() => {
    (telegramWidget.value as HTMLElement)!.appendChild(createLoginWidget());
});

onUnmounted(() => {
    if (window.onTelegramAuth) {
        delete window.onTelegramAuth;
    }
});
</script>

<template>
    <div ref="telegramWidget"></div>
</template>

<style scoped></style>
