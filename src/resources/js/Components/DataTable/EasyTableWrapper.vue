<script setup lang="ts">
import Vue3EasyDataTable, { Header, Item, ServerOptions } from 'vue3-easy-data-table';
import 'vue3-easy-data-table/dist/style.css';

const props = defineProps<{
    columns: Header[];
    items: Item[];
    renderPerPage: number[];
    currentPage: number;
    showPerPage: number;
    totalItems: number;
}>();

defineEmits<{
    (e: 'paginationUpdate', options: ServerOptions): void;
}>();

const options: ServerOptions = {
    page: props.currentPage,
    rowsPerPage: props.showPerPage,
};
</script>

<template>
    <Vue3EasyDataTable
        v-model:server-options="options"
        :headers="columns"
        :items="items"
        :rows-items="renderPerPage"
        :server-items-length="totalItems"
        table-class-name="dark-theme"
        show-index
        border-cell
        @update:server-options="$emit('paginationUpdate', options)"
    >
        <template v-for="slotName in Object.keys($slots)" #[slotName]="item: Item">
            <slot :name="slotName" v-bind="item" />
        </template>
    </Vue3EasyDataTable>
</template>

<style scoped>
.dark-theme {
    --easy-table-border: 1px solid #445269;
    --easy-table-row-border: 1px solid #445269;

    --easy-table-header-font-size: 14px;
    --easy-table-header-height: 50px;
    --easy-table-header-font-color: #c1cad4;
    --easy-table-header-background-color: #2d3a4f;

    --easy-table-header-item-padding: 10px 15px;

    --easy-table-body-even-row-font-color: #fff;
    --easy-table-body-even-row-background-color: #4c5d7a;

    --easy-table-body-row-font-color: #c0c7d2;
    --easy-table-body-row-background-color: #2d3a4f;
    --easy-table-body-row-height: 50px;
    --easy-table-body-row-font-size: 14px;

    --easy-table-body-row-hover-font-color: #2d3a4f;
    --easy-table-body-row-hover-background-color: #eee;

    --easy-table-body-item-padding: 10px 15px;

    --easy-table-footer-background-color: #2d3a4f;
    --easy-table-footer-font-color: #c0c7d2;
    --easy-table-footer-font-size: 14px;
    --easy-table-footer-padding: 0px 10px;
    --easy-table-footer-height: 50px;

    --easy-table-rows-per-page-selector-width: 70px;
    --easy-table-rows-per-page-selector-option-padding: 10px;
    --easy-table-rows-per-page-selector-z-index: 1;

    --easy-table-scrollbar-track-color: #2d3a4f;
    --easy-table-scrollbar-color: #2d3a4f;
    --easy-table-scrollbar-thumb-color: #4c5d7a;
    --easy-table-scrollbar-corner-color: #2d3a4f;

    --easy-table-loading-mask-background-color: #2d3a4f;
}
</style>
