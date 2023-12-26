<script setup lang="ts">
import DataTable, { DataTablePageEvent } from 'primevue/datatable';
import { Link, router } from '@inertiajs/vue3';
import Button from 'primevue/button';
import Column from 'primevue/column';
import { AnimePagination } from '@/entities/anime';

defineProps<{ pagination: AnimePagination }>();

const perPageOptions: number[] = [20, 50, 100];
const styles = {
    paginator: {
        current: {
            class: ['text-gray-600 dark:text-white'],
        },
    },
};

const handleUpdate = (event: DataTablePageEvent) => {
    router.get(
        route('anime.index'),
        {
            page: event.page + 1,
            per_page: event.rows,
        },
        {
            preserveScroll: true,
            only: ['pagination'],
        }
    );
};
</script>

<template>
    <div class="shadow-lg">
        <DataTable
            paginator
            lazy
            :first="(pagination.current_page - 1) * pagination.per_page"
            :value="pagination.data"
            :rows="pagination.per_page"
            :rows-per-page-options="perPageOptions"
            :total-records="pagination.total"
            :pt="styles"
            paginator-template="FirstPageLink PrevPageLink CurrentPageReport NextPageLink LastPageLink RowsPerPageDropdown"
            current-page-report-template="{first} to {last} of {totalRecords}"
            @page="handleUpdate"
        >
            <Column header="Title" field="title" :sortable="true" />
            <Column header="Status" field="status" :sortable="true" />
            <Column header="Rating" field="rating" :sortable="true" />
            <Column header="Episodes" field="episodes" :sortable="true" />
            <Column :exportable="false" style="min-width: 8rem">
                <template #body="slotProps">
                    <Link :href="route('anime.show', slotProps.data.id)" target="_blank">
                        <Button
                            icon="pi pi-pencil"
                            outlined
                            rounded
                            severity="success"
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
    </div>
</template>

<style scoped></style>
