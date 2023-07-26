<script setup lang="ts">
defineProps<{
    url: string;
    text: string;
}>();
</script>

<template>
    <a :href="url" target="_blank">{{ text }}</a>
</template>

<style scoped></style>
