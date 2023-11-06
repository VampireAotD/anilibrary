<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { IndexProps } from '@/types/anime/props';
import Modal from '@/Components/Modal.vue';
import AddAnimeForm from '@/Pages/Anime/Partials/AddAnimeForm.vue';
import { ref } from 'vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import Button from 'primevue/button';

defineProps<{
    pagination: IndexProps;
}>();

const renderPerPage: number[] = [20, 50, 100];
const handleUpdate = (options: unknown) => {
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
                    <PrimaryButton @click="showAddModal = true">
                        Add new anime
                    </PrimaryButton>
                </div>

                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <DataTable
                        class="dark:bg-gray-800"
                        paginator
                        lazy
                        :first="(pagination.current_page - 1) * pagination.per_page"
                        :value="pagination.data"
                        :rows="pagination.per_page"
                        :rows-per-page-options="renderPerPage"
                        :total-records="pagination.total"
                        paginator-template="RowsPerPageDropdown FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink"
                        current-page-report-template="{first} to {last} of {totalRecords}"
                        @page="
                            (event) =>
                                handleUpdate({
                                    page: event.page + 1,
                                    rowsPerPage: event.rows,
                                })
                        "
                    >
                        <Column header="Title" field="title" :sortable="true">
                            <template #body="{ data }">
                                <div class="flex align-items-center gap-2">
                                    <img
                                        :alt="data.title"
                                        :src="`${data.image.path}`"
                                        style="width: 48px"
                                    />
                                    <span>{{ data.title }}</span>
                                </div>
                            </template>
                        </Column>
                        <Column header="Status" field="status" :sortable="true" />
                        <Column header="Rating" field="rating" :sortable="true" />
                        <Column header="Episodes" field="episodes" :sortable="true" />
                        <Column :exportable="false" style="min-width: 8rem">
                            <template #body="slotProps">
                                <Link
                                    :href="route('anime.show', slotProps.data.id)"
                                    target="_blank"
                                >
                                    <Button
                                        icon="pi pi-pencil"
                                        outlined
                                        rounded
                                        class="mr-2"
                                    />
                                </Link>

                                <Button
                                    icon="pi pi-trash"
                                    outlined
                                    rounded
                                    severity="danger"
                                    @click="console.log(slotProps.data)"
                                />
                            </template>
                        </Column>
                    </DataTable>

                    <Modal :show="showAddModal" @close="showAddModal = false">
                        <AddAnimeForm @added="showAddModal = false" />
                    </Modal>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
