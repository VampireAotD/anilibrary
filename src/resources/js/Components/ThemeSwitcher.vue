<script setup lang="ts">
import Button from 'primevue/button';
import { useDark, useToggle } from '@vueuse/core';

const isDark = useDark();
const toggleDarkMode = useToggle(isDark);
</script>

<template>
    <Button
        class="text-white dark:text-black bg-dark dark:bg-white dark:hover:bg-gray-100"
        rounded
        severity="secondary"
        :icon="`pi ${isDark ? 'pi-sun' : 'pi-moon'}`"
        @click="toggleDarkMode()"
    />
</template>

<style scoped></style>
