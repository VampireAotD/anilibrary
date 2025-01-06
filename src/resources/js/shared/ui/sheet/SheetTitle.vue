<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import { DialogTitle, type DialogTitleProps } from 'radix-vue';

import { cn } from '@/shared/helpers/tailwind';

const props = defineProps<DialogTitleProps & { class?: HTMLAttributes['class'] }>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});
</script>

<template>
    <DialogTitle
        :class="cn('text-lg font-semibold text-foreground', props.class)"
        v-bind="delegatedProps"
    >
        <slot />
    </DialogTitle>
</template>
