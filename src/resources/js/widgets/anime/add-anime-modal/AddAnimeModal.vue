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
        <template #header>Choose how to add anime</template>

        <template #body>
            <div class="flex flex-col">
                <div
                    v-show="!displayCreateForm && !displayScrapeForm"
                    class="inline-flex gap-4"
                >
                    <Button class="scrape-option" @click="toggleScrapeFormVisibility">
                        Scrape
                    </Button>

                    <Button class="create-option" @click="toggleCreateFormVisibility">
                        Create
                    </Button>
                </div>

                <ScrapeAnimeForm v-if="displayScrapeForm" @added="close" />

                <CreateAnimeForm
                    v-if="displayCreateForm"
                    @added="close"
                    @cancel="close"
                />
            </div>
        </template>
    </Modal>
</template>

<style scoped></style>
