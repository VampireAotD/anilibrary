<script setup lang="ts">
import { AuthenticatedLayout } from '@/widgets/layouts';
import { Head } from '@inertiajs/vue3';
import { AnimePagination } from '@/entities/anime';
import { AnimeDataTable } from '@/features/anime/datatable';
import Button from 'primevue/button';
import { ref } from 'vue';
import { AddAnimeOptionModal } from '@/widgets/anime/add-anime-option-modal';

defineProps<{ pagination: AnimePagination }>();

const optionModalVisible = ref<boolean>(false);
</script>

<template>
    <Head title="Anime" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Anime
            </h2>
        </template>

        <div class="sm:p-6 lg:p-8">
            <div class="bg-white dark:bg-dark shadow mb-2 p-2">
                <Button
                    label="Add anime"
                    icon="pi pi-plus"
                    severity="success"
                    @click="optionModalVisible = true"
                />
            </div>

            <AnimeDataTable :pagination="pagination" />

            <AddAnimeOptionModal
                :visible="optionModalVisible"
                @close="optionModalVisible = false"
            />
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
