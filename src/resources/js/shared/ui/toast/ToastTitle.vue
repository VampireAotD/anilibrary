<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import { ToastTitle, type ToastTitleProps } from 'radix-vue';

import { cn } from '@/shared/helpers/tailwind';

const props = defineProps<ToastTitleProps & { class?: HTMLAttributes['class'] }>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});
</script>

<template>
    <ToastTitle v-bind="delegatedProps" :class="cn('text-sm font-semibold', props.class)">
        <slot />
    </ToastTitle>
</template>
