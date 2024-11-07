<script setup lang="ts">
import { ref } from 'vue';

import { CreateAnimeForm } from '@/features/anime/create-anime-form';
import { ScrapeAnimeForm } from '@/features/anime/scrape-anime-form';
import { Button } from '@/shared/ui/button';
import { Modal } from '@/shared/ui/modal';

type Props = {
    visible: boolean;
};

defineProps<Props>();
const emit = defineEmits<{ close: [] }>();

const displayScrapeForm = ref<boolean>(false);
const displayCreateForm = ref<boolean>(false);

const toggleScrapeFormVisibility = () =>
    (displayScrapeForm.value = !displayScrapeForm.value);

const toggleCreateFormVisibility = () =>
    (displayCreateForm.value = !displayCreateForm.value);

const close = () => {
    displayScrapeForm.value = false;
    displayCreateForm.value = false;

    emit('close');
};
</script>

<template>
    <Modal :visible="visible" close-on-escape close-on-outside-click @close="close">
        <template #header>
            <h2 id="modal-title" class="text-lg font-semibold text-foreground">
                Choose how to add anime
            </h2>
        </template>

        <template #body>
            <div class="flex flex-col" role="group" aria-label="Anime addition options">
                <div
                    v-show="!displayCreateForm && !displayScrapeForm"
                    class="scrape-options inline-flex gap-4"
                    role="group"
                    aria-label="Choose anime addition option"
                >
                    <Button
                        class="scrape-option"
                        @click="toggleScrapeFormVisibility"
                        aria-controls="scrape-form"
                    >
                        Scrape
                    </Button>

                    <Button
                        class="create-option"
                        @click="toggleCreateFormVisibility"
                        aria-controls="scrape-form"
                    >
                        Create
                    </Button>
                </div>

                <ScrapeAnimeForm
                    v-if="displayScrapeForm"
                    @added="close"
                    id="scrape-form"
                    role="region"
                    aria-label="Scrape anime form"
                />

                <CreateAnimeForm
                    v-if="displayCreateForm"
                    @added="close"
                    @cancel="close"
                    id="create-form"
                    role="region"
                    aria-label="Create anime form"
                />
            </div>
        </template>
    </Modal>
</template>

<style scoped></style>
