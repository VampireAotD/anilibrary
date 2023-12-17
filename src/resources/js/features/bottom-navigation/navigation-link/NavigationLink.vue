<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

type Props = {
    url: string;
    text: string;
};

defineProps<Props>();
</script>

<template>
    <Link
        :href="url"
        class="inline-flex flex-col items-center justify-center px-5 border-gray-400 border-x hover:bg-gray-50 dark:hover:bg-gray-800 group dark:border-gray-600"
    >
        <slot />

        <span
            class="text-sm text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-red-500"
        >
            {{ text }}
        </span>
    </Link>
</template>

<style scoped></style>
