<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import {
    ComboboxRoot,
    type ComboboxRootEmits,
    type ComboboxRootProps,
    useForwardPropsEmits,
} from 'radix-vue';

import { cn } from '@/shared/helpers/tailwind';

const props = withDefaults(
    defineProps<ComboboxRootProps & { class?: HTMLAttributes['class'] }>(),
    {
        open: true,
        modelValue: '',
    }
);

const emits = defineEmits<ComboboxRootEmits>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});

const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
    <ComboboxRoot
        v-bind="forwarded"
        :class="
            cn(
                'flex h-full w-full flex-col overflow-hidden rounded-md bg-popover text-popover-foreground',
                props.class
            )
        "
    >
        <slot />
    </ComboboxRoot>
</template>
