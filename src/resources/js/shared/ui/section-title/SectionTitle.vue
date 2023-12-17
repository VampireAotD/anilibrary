<script setup lang="ts">
type Props = {
    title: string;
};

defineProps<Props>();
</script>

<template>
    <div class="px-3 border-l-4 border-black dark:border-red-500">
        <h3 class="text-black dark:text-white uppercase">
            {{ title }}
        </h3>
    </div>
</template>

<style scoped></style>
