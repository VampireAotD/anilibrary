<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import { ToastViewport, type ToastViewportProps } from 'reka-ui';

import { cn } from '@/shared/helpers/tailwind';

const props = defineProps<ToastViewportProps & { class?: HTMLAttributes['class'] }>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});
</script>

<template>
    <ToastViewport
        v-bind="delegatedProps"
        :class="
            cn(
                'fixed top-0 z-100 flex max-h-screen w-full flex-col-reverse p-4 sm:bottom-0 sm:top-auto sm:left-1/2 sm:-translate-x-1/2 sm:flex-col md:max-w-[420px]',
                props.class
            )
        "
    />
</template>
