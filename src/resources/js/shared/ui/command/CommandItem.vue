<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import {
    ComboboxItem,
    type ComboboxItemEmits,
    type ComboboxItemProps,
    useForwardPropsEmits,
} from 'radix-vue';

import { cn } from '@/shared/helpers/tailwind';

const props = defineProps<ComboboxItemProps & { class?: HTMLAttributes['class'] }>();
const emits = defineEmits<ComboboxItemEmits>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});

const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
    <ComboboxItem
        v-bind="forwarded"
        :class="
            cn(
                'relative flex cursor-default select-none items-center rounded-sm px-2 py-1.5 text-sm outline-none data-[highlighted]:bg-accent data-[highlighted]:text-accent-foreground data-[disabled]:pointer-events-none data-[disabled]:opacity-50',
                props.class
            )
        "
    >
        <slot />
    </ComboboxItem>
</template>
