<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import { ChevronDown } from 'lucide-vue-next';
import {
    SelectScrollDownButton,
    type SelectScrollDownButtonProps,
    useForwardProps,
} from 'radix-vue';

import { cn } from '@/shared/helpers/tailwind';

const props = defineProps<
    SelectScrollDownButtonProps & { class?: HTMLAttributes['class'] }
>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
    <SelectScrollDownButton
        v-bind="forwardedProps"
        :class="cn('flex cursor-default items-center justify-center py-1', props.class)"
    >
        <slot>
            <ChevronDown class="h-4 w-4" />
        </slot>
    </SelectScrollDownButton>
</template>
