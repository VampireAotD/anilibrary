<script setup lang="ts">
import TelegramLoginWidget from '@/Components/TelegramLoginWidget.vue';
import { TelegramUser } from '@/types/telegram/types';
import { router, usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import Button from '@/Components/Button.vue';

const page = usePage();
const telegramUser = computed(() => page.props.auth.user.telegram_user);

const handleTelegramLogin = (user: TelegramUser) => {
    const payload = { ...user };

    router.post(route('telegram.assign'), payload, {
        preserveScroll: true,
    });
};

const revokeTelegramAccount = () => {
    router.delete(route('telegram.detach'), {
        preserveScroll: true,
    });
};
</script>

<template>
    <section>
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Integrations
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Connect your Anilibrary account with other social media
            </p>
        </header>

        <div class="mt-4 flex items-center gap-4">
            <div class="p-2 rounded-lg">
                <h3 class="mb-2 text-md font-medium text-gray-900 dark:text-gray-100">
                    Telegram
                </h3>

                <TelegramLoginWidget
                    v-if="!telegramUser"
                    :callback="handleTelegramLogin"
                />

                <div v-else class="flex flex-col">
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Connected as
                        {{ telegramUser.username ?? telegramUser.telegram_id }}
                    </p>

                    <Button color="danger" @click="revokeTelegramAccount">
                        Revoke Telegram account
                    </Button>
                </div>
            </div>
        </div>
    </section>
</template>

<style scoped></style>
