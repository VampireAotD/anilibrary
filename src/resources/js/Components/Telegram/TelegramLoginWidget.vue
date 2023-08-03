<script setup lang="ts">
import { onMounted, onUnmounted, ref } from 'vue';
import { RequestAccess, TelegramWidgetProps, WidgetSize } from '@/types/telegram/types';

const props = withDefaults(defineProps<TelegramWidgetProps>(), {
    botName: 'anilibrary_bot',
    size: WidgetSize.Medium,
    radius: 14,
    showUserPic: false,
    requestAccess: RequestAccess.Write,
});

const telegramWidget = ref<HTMLElement | null>(null);

const createLoginWidget = (): HTMLElement => {
    const script = document.createElement('script');
    script.async = true;
    script.src = 'https://telegram.org/js/telegram-widget.js?22';

    script.setAttribute('data-telegram-login', props.botName);
    script.setAttribute('data-size', props.size);
    script.setAttribute('data-radius', props.radius);
    script.setAttribute('data-userpic', props.showUserPic.toString());
    script.setAttribute('data-request-access', props.requestAccess);

    if (!props.redirectUrl) {
        window.onTelegramAuth = props.callbackHandler;
        script.setAttribute('data-onauth', 'onTelegramAuth(user)');
    } else {
        script.setAttribute('data-auth-url', route('telegram.login'));
    }

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
