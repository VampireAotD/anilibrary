<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import { SelectSeparator, type SelectSeparatorProps } from 'radix-vue';

import { cn } from '@/shared/helpers/tailwind';

const props = defineProps<SelectSeparatorProps & { class?: HTMLAttributes['class'] }>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});
</script>

<template>
    <SelectSeparator
        v-bind="delegatedProps"
        :class="cn('-mx-1 my-1 h-px bg-muted', props.class)"
    />
</template>
