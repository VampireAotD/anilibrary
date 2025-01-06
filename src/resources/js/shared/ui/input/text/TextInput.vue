<script setup lang="ts">
import { onMounted, useTemplateRef } from 'vue';

import { cn } from '@/shared/helpers/tailwind';

defineExpose({ focus: () => input.value?.focus() });

const model = defineModel<string>();
const input = useTemplateRef<HTMLInputElement>('input');

onMounted(() => {
    if (input.value?.hasAttribute('autofocus')) {
        input.value?.focus();
    }
});
</script>

<template>
    <input
        ref="input"
        v-model="model"
        :class="
            cn(
                'flex h-10 w-full rounded-md border border-input bg-background px-3 py-2 text-sm ring-offset-background file:border-0 file:bg-transparent file:text-sm file:font-medium placeholder:text-muted-foreground focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring disabled:cursor-not-allowed disabled:opacity-50'
            )
        "
    />
</template>
