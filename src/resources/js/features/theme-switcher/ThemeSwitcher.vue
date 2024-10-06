<script setup lang="ts">
import { useDark, useToggle } from '@vueuse/core';

import { Moon, Sun } from 'lucide-vue-next';

import { Button } from '@/shared/ui/button';

const isDark = useDark();
const toggleDarkMode = useToggle(isDark);
</script>

<template>
    <Button
        class="text-dark bg-dark dark:bg-white dark:hover:bg-gray-100"
        rounded
        variant="secondary"
        @click="toggleDarkMode()"
    >
        <Sun v-if="isDark" />
        <Moon v-else />
    </Button>
</template>

<style scoped></style>
