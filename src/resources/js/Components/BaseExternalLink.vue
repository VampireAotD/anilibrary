<script setup lang="ts">
type Props = {
    url: string;
    text: string;
};

defineProps<Props>();
</script>

<template>
    <a
        :href="url"
        target="_blank"
        class="force-no-underline border-b-2 border-transparent text-sm font-medium leading-5 text-gray-500 dark:text-gray-100 hover:text-gray-700 dark:hover:text-gray-300 hover:border-gray-300 dark:hover:border-red-500 focus:outline-none focus:text-gray-700 dark:focus:text-gray-300 transition duration-150 ease-in-out"
    >
        {{ text }}
    </a>
</template>

<style scoped>
.force-no-underline {
    text-decoration: none !important;
}
</style>
