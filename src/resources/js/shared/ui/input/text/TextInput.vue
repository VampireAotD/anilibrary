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
                'px-5 py-3 bg-background text-foreground text-xs rounded-md shadow-sm focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600'
            )
        "
    />
</template>
