<script setup lang="ts">
import { AuthenticatedLayout } from '@/widgets/layouts';
import { Head } from '@inertiajs/vue3';
import { AnimePagination } from '@/entities/anime';
import { AnimeDataTable } from '@/features/anime/datatable';

defineProps<{
    pagination: AnimePagination;
}>();
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
            <AnimeDataTable :pagination="pagination" />
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
