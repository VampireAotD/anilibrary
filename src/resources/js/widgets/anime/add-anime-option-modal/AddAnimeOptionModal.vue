<script setup lang="ts">
import { ref } from 'vue';

import { CreateAnimeModal } from '@/features/anime/create-anime-modal';
import { ScrapeAnimeModal } from '@/features/anime/scrape-anime-modal';
import { Button } from '@/shared/ui/button';
import { Modal } from '@/shared/ui/modal';

type Props = {
    visible: boolean;
};

defineProps<Props>();
const emit = defineEmits<{ close: [] }>();

const displayScrapeModal = ref<boolean>(false);
const displayCreateModal = ref<boolean>(false);

const toggleScrapeModalVisibility = () => {
    displayScrapeModal.value = !displayScrapeModal.value;
    emit('close');
};

const toggleCreateModalVisibility = () => {
    displayCreateModal.value = !displayCreateModal.value;
    emit('close');
};
</script>

<template>
    <Modal
        :visible="visible"
        close-on-escape
        close-on-outside-click
        @close="$emit('close')"
    >
        <template #header> Choose how to add anime</template>

        <template #body>
            <div class="inline-flex gap-4">
                <Button class="scrape-option" @click="toggleScrapeModalVisibility">
                    Scrape
                </Button>

                <Button class="create-option" @click="toggleCreateModalVisibility">
                    Create
                </Button>
            </div>
        </template>
    </Modal>

    <ScrapeAnimeModal
        v-if="displayScrapeModal"
        :visible="displayScrapeModal"
        @close="toggleScrapeModalVisibility"
    />

    <CreateAnimeModal
        v-if="displayCreateModal"
        :visible="displayCreateModal"
        @close="toggleCreateModalVisibility"
    />
</template>

<style scoped></style>
