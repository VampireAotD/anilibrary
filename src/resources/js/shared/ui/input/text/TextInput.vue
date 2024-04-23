<script setup lang="ts">
import { onMounted, ref } from 'vue';

defineExpose({ focus: () => input.value?.focus() });

const model = defineModel<string>();
const input = ref<HTMLInputElement | null>(null);

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value?.focus();
    }
});
</script>

<template>
    <input
        ref="input"
        v-model="model"
        class="text-black border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
    />
</template>
