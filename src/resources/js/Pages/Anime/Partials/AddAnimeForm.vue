<script setup lang="ts">
import TextInput from '@/Components/Input/TextInput.vue';
import InputLabel from '@/Components/Input/InputLabel.vue';
import InputError from '@/Components/Input/InputError.vue';
import PrimaryButton from '@/Components/Button/PrimaryButton.vue';
import { useForm, usePage } from '@inertiajs/vue3';
import { ScrapeResult } from '@/types/pusher/types';
import { useToast } from 'primevue/usetoast';

const page = usePage();
const toast = useToast();

const emit = defineEmits<{
    (e: 'added', url: string): void;
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
    <section class="p-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Add new anime
            </h2>
        </header>

        <form class="mt-6 space-y-6" @submit.prevent="addAnime">
            <div>
                <InputLabel for="url" value="URL" />

                <TextInput
                    id="url"
                    ref="url"
                    v-model="form.url"
                    type="url"
                    class="mt-1 block w-full"
                    autocomplete="url"
                />

                <InputError :message="form.errors.url" class="mt-2" />
            </div>

            <div class="flex items-center gap-4">
                <PrimaryButton :disabled="form.processing">Add</PrimaryButton>
            </div>

            <Toast position="bottom-center" />
        </form>
    </section>
</template>

<style scoped></style>
