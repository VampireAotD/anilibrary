<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import { ComboboxEmpty, type ComboboxEmptyProps } from 'radix-vue';

import { cn } from '@/shared/helpers/tailwind';

const props = defineProps<ComboboxEmptyProps & { class?: HTMLAttributes['class'] }>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});
</script>

<template>
    <ComboboxEmpty
        v-bind="delegatedProps"
        :class="cn('py-6 text-center text-sm', props.class)"
    >
        <slot />
    </ComboboxEmpty>
</template>
