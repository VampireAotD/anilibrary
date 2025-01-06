<script setup lang="ts">
import { useForm, usePage } from '@inertiajs/vue3';

import { ScrapeResult } from '@/entities/scraper';
import { Button } from '@/shared/ui/button';
import { ErrorMessage } from '@/shared/ui/error-message';
import { TextInput } from '@/shared/ui/input';
import { Label } from '@/shared/ui/label';
import { useToast } from '@/shared/ui/toast';

const emit = defineEmits<{ added: [url: string] }>();

const page = usePage();
const { toast } = useToast();

const form = useForm({
    url: '',
});

const addAnime = () => {
    form.post(route('anime.store'), {
        preserveScroll: true,
        onSuccess: () => {
            const channelName = `scrape.anime.${page.props.auth?.user?.id}`;

            toast({
                title: page.props.flash.message,
            });

            emit('added', form.url);

            window.echo
                .private(channelName)
                .listen('.scrape.anime.result', (result: ScrapeResult) => {
                    toast({
                        title: result.message,
                    });

                    window.echo.leave(channelName);
                })
                .error(() => {
                    toast({
                        title: 'Error while establishing websocket connection',
                        variant: 'destructive',
                    });

                    window.echo.leave(channelName);
                });
        },
        onError: (error) => {
            toast({
                title: error?.url ?? 'Unexpected error',
                variant: 'destructive',
            });
        },
        onFinish: () => {
            form.reset();
        },
    });
};
</script>

<template>
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

<style scoped></style>
