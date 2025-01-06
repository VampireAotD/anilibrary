<script setup lang="ts">
import { ComponentPublicInstance, onMounted, onUnmounted, useTemplateRef } from 'vue';

import { Button } from '@/shared/ui/button';

const emit = defineEmits<{ search: [] }>();

const button = useTemplateRef<ComponentPublicInstance<HTMLButtonElement>>('searchButton');

const activateSearch = (event: KeyboardEvent) => {
    if (event.ctrlKey && event.key === 'k') {
        event.preventDefault();

        button.value?.$el.click();
        button.value?.$el.blur();

        emit('search');
    }
};

onMounted(() => window.addEventListener('keydown', activateSearch));
onUnmounted(() => window.removeEventListener('keydown', activateSearch));
</script>

<template>
    <Button
        ref="searchButton"
        variant="outline"
        class="p-5 sm:p-3 relative h-8 w-full justify-start rounded-[0.5rem] text-sm font-normal text-muted-foreground shadow-none sm:pr-12 md:w-40 lg:w-56 xl:w-64"
    >
        <span class="inline-flex">Search anime...</span>
        <kbd
            class="pointer-events-none absolute right-[0.3rem] top-[0.3rem] hidden h-5 select-none items-center gap-1 rounded border bg-muted px-1.5 font-mono text-[10px] font-medium opacity-100 sm:flex"
        >
            <span class="text-xs">⌘</span>
            K
        </kbd>
    </Button>
</template>

<style scoped></style>
