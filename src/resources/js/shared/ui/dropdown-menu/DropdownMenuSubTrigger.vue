<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import { ChevronRight } from 'lucide-vue-next';
import {
    DropdownMenuSubTrigger,
    type DropdownMenuSubTriggerProps,
    useForwardProps,
} from 'radix-vue';

import { cn } from '@/shared/helpers/tailwind';

const props = defineProps<
    DropdownMenuSubTriggerProps & { class?: HTMLAttributes['class'] }
>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
    <DropdownMenuSubTrigger
        v-bind="forwardedProps"
        :class="
            cn(
                'flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none focus:bg-accent data-[state=open]:bg-accent',
                props.class
            )
        "
    >
        <slot />
        <ChevronRight class="ml-auto h-4 w-4" />
    </DropdownMenuSubTrigger>
</template>
