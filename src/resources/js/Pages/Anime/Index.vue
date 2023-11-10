<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head } from '@inertiajs/vue3';
import { AnimePagination } from '@/types/anime/types';
import DataTable from '@/Pages/Anime/Partials/DataTable.vue';

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
            <DataTable :pagination="pagination" />
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
