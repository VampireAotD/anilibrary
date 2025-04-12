<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import { DropdownMenuItem, type DropdownMenuItemProps, useForwardProps } from 'reka-ui';

import { cn } from '@/shared/helpers/tailwind';

const props = defineProps<
    DropdownMenuItemProps & { class?: HTMLAttributes['class']; inset?: boolean }
>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
    <DropdownMenuItem
        v-bind="forwardedProps"
        :class="
            cn(
                'relative flex cursor-default select-none items-center rounded-sm gap-2 px-2 py-1.5 text-sm outline-hidden transition-colors focus:bg-accent focus:text-accent-foreground data-disabled:pointer-events-none data-disabled:opacity-50  [&>svg]:size-4 [&>svg]:shrink-0',
                inset && 'pl-8',
                props.class
            )
        "
    >
        <slot />
    </DropdownMenuItem>
</template>
