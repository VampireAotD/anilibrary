<script setup lang="ts">
import { computed } from 'vue';

import { router, usePage } from '@inertiajs/vue3';

import { TelegramUser } from '@/entities/telegram-user';
import { TelegramLoginWidget } from '@/features/telegram/login-widget';
import { Block } from '@/shared/ui/block';
import { useToast } from '@/shared/ui/toast';

const page = usePage();
const { toast } = useToast();
const telegramUser = computed(() => page.props.auth.user.telegram_user);

const handleTelegramLogin = (user: TelegramUser) => {
    const payload = { ...user };

    router.post(route('telegram.assign'), payload, {
        preserveScroll: true,
        onError: (response) => {
            toast({
                title: response?.message,
                variant: 'destructive',
            });
        },
    });
};
</script>

<template>
    <Block as="section" class="sm:p-8">
        <header>
            <h2 class="text-lg font-medium">Integrations</h2>

            <p class="mt-1 text-sm">
                Connect your Anilibrary account with other social media
            </p>
        </header>

        <div class="mt-4 flex items-center gap-4">
            <div class="p-2 rounded-lg">
                <h3 class="mb-2 text-md font-medium">Telegram</h3>

                <TelegramLoginWidget
                    v-if="!telegramUser"
                    :callback="handleTelegramLogin"
                />

                <div v-else class="flex flex-col">
                    <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                        Connected as
                        {{ telegramUser.username ?? telegramUser.telegram_id }}
                    </p>
                </div>
            </div>
        </div>
    </Block>
</template>

<style scoped></style>
