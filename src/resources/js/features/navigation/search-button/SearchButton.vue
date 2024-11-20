<script setup lang="ts">
import { ComponentPublicInstance, onMounted, onUnmounted, useTemplateRef } from 'vue';

import { Search } from 'lucide-vue-next';

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
    <Button ref="searchButton" variant="outline" class="w-36 justify-between">
        <Search />
        <span>
            <kbd class="text-sm">CTRL + K</kbd>
        </span>
    </Button>
</template>

<style scoped></style>
