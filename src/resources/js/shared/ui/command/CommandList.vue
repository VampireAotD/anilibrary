<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import {
    ComboboxContent,
    type ComboboxContentEmits,
    type ComboboxContentProps,
    useForwardPropsEmits,
} from 'radix-vue';

import { cn } from '@/shared/helpers/tailwind';

const props = withDefaults(
    defineProps<ComboboxContentProps & { class?: HTMLAttributes['class'] }>(),
    {
        dismissable: false,
    }
);
const emits = defineEmits<ComboboxContentEmits>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});

const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
    <ComboboxContent
        v-bind="forwarded"
        :class="cn('max-h-[300px] overflow-y-auto overflow-x-hidden', props.class)"
    >
        <div role="presentation">
            <slot />
        </div>
    </ComboboxContent>
</template>
