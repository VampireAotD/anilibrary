<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import { ToastDescription, type ToastDescriptionProps } from 'radix-vue';

import { cn } from '@/shared/helpers/tailwind';

const props = defineProps<ToastDescriptionProps & { class?: HTMLAttributes['class'] }>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});
</script>

<template>
    <ToastDescription
        :class="cn('text-sm opacity-90', props.class)"
        v-bind="delegatedProps"
    >
        <slot />
    </ToastDescription>
</template>
