<script setup lang="ts">
import Button from 'primevue/button';
import { useDark, useToggle } from '@vueuse/core/index';

const isDark = useDark();
const toggleDarkMode = useToggle(isDark);
</script>

<template>
    <Button
        class="text-black dark:text-white"
        rounded
        :icon="`pi ${isDark ? 'pi-sun' : 'pi-moon'}`"
        @click="toggleDarkMode()"
    />
</template>

<style scoped></style>
