<script setup lang="ts">
import { computed } from 'vue';

import { ToastRoot, type ToastRootEmits, useForwardPropsEmits } from 'reka-ui';

import { cn } from '@/shared/helpers/tailwind';

import { type ToastProps, toastVariants } from '.';

const props = defineProps<ToastProps>();

const emits = defineEmits<ToastRootEmits>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});

const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
    <ToastRoot
        v-bind="forwarded"
        :class="cn(toastVariants({ variant }), props.class)"
        @update:open="onOpenChange"
    >
        <slot />
    </ToastRoot>
</template>
