<script setup lang="ts">
import BaseTextInput from '@/Components/BaseTextInput.vue';
import InputLabel from '@/Components/Input/InputLabel.vue';
import InputError from '@/Components/Input/InputError.vue';
import PrimaryButton from '@/Components/Button/PrimaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ScrapeResult } from '@/types/pusher/types';
import { useToast } from 'primevue/usetoast';
import BaseModal from '@/Components/BaseModal.vue';

const page = usePage();
const toast = useToast();

defineProps<{
    visible: boolean;
}>();

const emit = defineEmits<{
    (e: 'added', url: string): void;
    (e: 'close'): void;
}>();

const form = useForm({
    url: '',
});

const addAnime = () => {
    form.post(route('anime.store'), {
        onSuccess: () => {
            const channelName = `scraper.${page.props.auth?.user?.id}`;

            toast.add({
                summary: page.props.flash.message,
            });

            emit('added', form.url);

            window.echo
                .private(channelName)
                .listen('.scrape.result', (result: ScrapeResult) => {
                    toast.add({
                        summary: result.message,
                        severity: result.type,
                        closable: true,
                    });

                    window.echo.leave(channelName);
                })
                .error(() => {
                    toast.add({
                        summary: 'Error while establishing Pusher connection',
                        severity: 'error',
                        closable: true,
                    });

                    window.echo.leave(channelName);
                });
        },
        onError: (error) => {
            toast.add({
                summary: error?.url ?? 'Unexpected error',
                severity: 'error',
                closable: true,
            });
        },
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
    <BaseModal
        :visible="visible"
        close-on-escape
        close-on-outside-click
        @close="emit('close')"
    >
        <template #header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Add new anime
            </h2>
        </template>

        <template #body>
            <form @submit.prevent="addAnime">
                <div>
                    <InputLabel for="url" value="URL" />

                    <BaseTextInput
                        id="url"
                        ref="url"
                        v-model="form.url"
                        type="url"
                        class="mt-1 block w-full"
                        autocomplete="url"
                    />

                    <InputError :message="form.errors.url" class="mt-2" />
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <PrimaryButton :disabled="form.processing">Add</PrimaryButton>
                </div>
            </form>
        </template>
    </BaseModal>

    <Toast position="bottom-center" />
</template>

<style scoped></style>
