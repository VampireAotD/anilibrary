<script setup lang="ts">
import { useDark, useToggle } from '@vueuse/core';

import { Moon, Sun } from 'lucide-vue-next';

import { Button } from '@/shared/ui/button';

const isDark = useDark();
const toggleDarkMode = useToggle(isDark);
</script>

<template>
    <Button
        v-ripple
        class="size-8"
        variant="outline"
        size="rounded"
        @click="toggleDarkMode()"
    >
        <Sun v-if="isDark" />
        <Moon v-else />
    </Button>
</template>
