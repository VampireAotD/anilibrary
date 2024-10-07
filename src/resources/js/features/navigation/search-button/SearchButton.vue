<script setup lang="ts">
import { ComponentPublicInstance, onMounted, onUnmounted, ref } from 'vue';

import { Search } from 'lucide-vue-next';

import { Button } from '@/shared/ui/button';

const emit = defineEmits<{ search: [] }>();

const buttonRef = ref<ComponentPublicInstance<HTMLButtonElement> | null>(null);

const activateSearch = (event: KeyboardEvent) => {
    if (event.ctrlKey && event.key === 'k') {
        event.preventDefault();

        buttonRef.value?.$el.click();
        buttonRef.value?.$el.blur();

        emit('search');
    }
};

onMounted(() => window.addEventListener('keydown', activateSearch));
onUnmounted(() => window.removeEventListener('keydown', activateSearch));
</script>

<template>
    <Button ref="buttonRef" variant="outline" class="w-36 justify-between">
        <Search />
        <span>
            <kbd class="text-sm">CTRL + K</kbd>
        </span>
    </Button>
</template>

<style scoped></style>
