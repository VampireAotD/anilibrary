<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import { ToastAction, type ToastActionProps } from 'reka-ui';

import { cn } from '@/shared/helpers/tailwind';

const props = defineProps<ToastActionProps & { class?: HTMLAttributes['class'] }>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});
</script>

<template>
    <ToastAction
        v-bind="delegatedProps"
        :class="
            cn(
                'inline-flex h-8 shrink-0 items-center justify-center rounded-md border bg-transparent px-3 text-sm font-medium ring-offset-background transition-colors hover:bg-secondary focus:outline-hidden focus:ring-2 focus:ring-ring focus:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 group-[.destructive]:border-muted/40 hover:group-[.destructive]:border-destructive/30 hover:group-[.destructive]:bg-destructive hover:group-[.destructive]:text-destructive-foreground focus:group-[.destructive]:ring-destructive',
                props.class
            )
        "
    >
        <slot />
    </ToastAction>
</template>
