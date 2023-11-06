<script setup lang="ts">
import InputText from 'primevue/inputtext';
import { ref } from 'vue';

const query = ref<string | null>(null);
</script>

<template>
    <span class="p-input-icon-left">
        <i class="pi pi-search text-zinc-600 dark:text-white" />
        <InputText
            v-model="query"
            class="text-xs ring-1 bg-transparent ring-gray-200 dark:ring-zinc-600 focus:ring-red-300 pl-10 pr-5 text-gray-600 dark:text-white py-3 rounded-full w-full outline-none focus:ring-1"
            placeholder="Search"
        />
    </span>
</template>

<style scoped></style>
