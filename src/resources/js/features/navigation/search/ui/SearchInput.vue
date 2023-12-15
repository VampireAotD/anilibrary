<script setup lang="ts">
import InputText from 'primevue/inputtext';
import { ComponentPublicInstance, onMounted, ref } from 'vue';

const query = ref<string | null>(null);
const searchInput = ref<InputText | null>(null);

const focus = (event: KeyboardEvent) => {
    if (event.ctrlKey && event.key === 'k') {
        event.preventDefault();

        const input = searchInput.value as ComponentPublicInstance<{
            $el: HTMLInputElement;
        }>;
        input?.$el?.focus();
    }
};

onMounted(() => window.addEventListener('keydown', focus));
</script>

<template>
    <span class="p-input-icon-left">
        <i class="pi pi-search text-zinc-600 dark:text-white" />

        <InputText
            ref="searchInput"
            v-model="query"
            name="search"
            class="text-xs ring-1 bg-transparent ring-gray-200 dark:ring-zinc-600 focus:ring-red-300 pl-10 pr-5 text-gray-600 dark:text-white py-3 rounded-full w-full outline-none focus:ring-1"
            placeholder="Search (CTRL + K)"
        />
    </span>
</template>

<style scoped></style>
