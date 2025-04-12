<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import { ComboboxSeparator, type ComboboxSeparatorProps } from 'reka-ui';

import { cn } from '@/shared/helpers/tailwind';

const props = defineProps<ComboboxSeparatorProps & { class?: HTMLAttributes['class'] }>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});
</script>

<template>
    <ComboboxSeparator
        v-bind="delegatedProps"
        :class="cn('-mx-1 h-px bg-border', props.class)"
    >
        <slot />
    </ComboboxSeparator>
</template>
