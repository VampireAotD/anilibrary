<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { IndexProps } from '@/types/anime/props';
import { Header, ServerOptions } from 'vue3-easy-data-table';
import EasyTableWrapper from '@/Components/DataTable/EasyTableWrapper.vue';
import { AnimeWithRelations } from '@/types/anime/models';
import Modal from '@/Components/Modal.vue';
import AddAnimeForm from '@/Pages/Anime/Partials/AddAnimeForm.vue';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';

defineProps<{
    pagination: IndexProps;
}>();

const headers: Header[] = [
    { text: 'Title', value: 'title', width: 400 },
    { text: 'Status', value: 'status' },
    { text: 'Rating', value: 'rating' },
    { text: 'Episodes', value: 'episodes' },
];
const renderPerPage: number[] = [5, 10, 20, 50, 100];
const handleUpdate = (options: ServerOptions) => {
    router.get(
        route('anime.index'),
        { page: options.page, per_page: options.rowsPerPage },
        { preserveScroll: true }
    );
};
const showAddModal = ref<boolean>(false);
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

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="mb-2">
                    <PrimaryButton @click="showAddModal = true"
                        >Add new anime</PrimaryButton
                    >
                </div>

                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <EasyTableWrapper
                        :columns="headers"
                        :items="pagination.data"
                        :render-per-page="renderPerPage"
                        :current-page="pagination.current_page"
                        :show-per-page="pagination.per_page"
                        :total-items="pagination.total"
                        @pagination-update="handleUpdate"
                    >
                        <template #item-title="item: AnimeWithRelations">
                            <div class="flex items-center gap-x-3">
                                <img
                                    class="object-cover w-10 h-10 rounded-full"
                                    :src="item.image.path"
                                    :alt="item.title"
                                />

                                <Link
                                    :href="route('anime.show', item.id)"
                                    target="_blank"
                                    class="w-[300px]"
                                >
                                    <p
                                        class="truncate font-medium transition-all hover:text-primary decoration-gray-200 hover:decoration-primary underline underline-offset-4"
                                    >
                                        {{ item.title }}
                                    </p>
                                </Link>
                            </div>
                        </template>
                    </EasyTableWrapper>

                    <Modal :show="showAddModal" @close="showAddModal = false">
                        <AddAnimeForm @added="showAddModal = false" />
                    </Modal>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
