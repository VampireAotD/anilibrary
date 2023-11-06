<script setup lang="ts">
type Props = {
    title: string;
    value: number;
};

defineProps<Props>();
</script>

<template>
    <div
        class="w-full bg-white dark:bg-gray-800 border border-gray-300 shadow dark:border-red-500 rounded-lg flex items-center p-6"
    >
        <slot name="icon" />

        <div>
            <p class="font-semibold text-black dark:text-white">{{ value }}</p>
            <p class="font-semibold text-black dark:text-white">{{ title }}</p>
        </div>
    </div>
</template>

<style scoped></style>
