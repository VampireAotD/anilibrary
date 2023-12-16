<script setup lang="ts">
import { TextInput } from '@/shared/ui/input';
import { Label } from '@/shared/ui/label';
import { ErrorMessage } from '@/shared/ui/error-message';
import { useForm, usePage } from '@inertiajs/vue3';
import { ScrapeResult } from '@/entities/pusher';
import { useToast } from 'primevue/usetoast';
import { Modal } from '@/shared/ui/modal';
import { Button } from '@/shared/ui/button';

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
    <Modal
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
                    <Label for="url" value="URL" />

                    <TextInput
                        id="url"
                        ref="url"
                        v-model="form.url"
                        type="url"
                        class="mt-1 block w-full"
                        autocomplete="url"
                    />

                    <ErrorMessage :message="form.errors.url" class="mt-2" />
                </div>

                <div class="mt-6 flex items-center gap-4">
                    <Button type="submit" :disabled="form.processing">Add</Button>
                </div>
            </form>
        </template>
    </Modal>

    <Toast position="bottom-center" />
</template>

<style scoped></style>
