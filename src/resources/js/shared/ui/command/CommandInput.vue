<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import { Search } from 'lucide-vue-next';
import { ComboboxInput, type ComboboxInputProps, useForwardProps } from 'reka-ui';

import { cn } from '@/shared/helpers/tailwind';

defineOptions({
    inheritAttrs: false,
});

const props = defineProps<
    ComboboxInputProps & {
        class?: HTMLAttributes['class'];
    }
>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});

const forwardedProps = useForwardProps(delegatedProps);
</script>

<template>
    <div class="flex items-center border-b px-3" cmdk-input-wrapper>
        <Search class="mr-2 h-4 w-4 shrink-0 opacity-50" />
        <ComboboxInput
            v-bind="{ ...forwardedProps, ...$attrs }"
            auto-focus
            :class="
                cn(
                    'flex h-11 w-full rounded-md bg-transparent py-3 text-sm outline-hidden placeholder:text-muted-foreground disabled:cursor-not-allowed disabled:opacity-50',
                    props.class
                )
            "
        />
    </div>
</template>
