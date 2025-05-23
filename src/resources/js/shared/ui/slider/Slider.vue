<script setup lang="ts">
import { type HTMLAttributes, computed } from 'vue';

import {
    SliderRange,
    SliderRoot,
    type SliderRootEmits,
    type SliderRootProps,
    SliderThumb,
    SliderTrack,
    useForwardPropsEmits,
} from 'radix-vue';

import { cn } from '@/shared/helpers/tailwind';

const props = defineProps<SliderRootProps & { class?: HTMLAttributes['class'] }>();
const emits = defineEmits<SliderRootEmits>();

const delegatedProps = computed(() => {
    // eslint-disable-next-line @typescript-eslint/no-unused-vars
    const { class: _, ...delegated } = props;

    return delegated;
});

const forwarded = useForwardPropsEmits(delegatedProps, emits);
</script>

<template>
    <SliderRoot
        :class="
            cn(
                'relative flex w-full touch-none select-none items-center data-[orientation=vertical]:flex-col data-[orientation=vertical]:w-2 data-[orientation=vertical]:h-full',
                props.class
            )
        "
        v-bind="forwarded"
    >
        <SliderTrack
            class="relative h-2 w-full data-[orientation=vertical]:w-2 grow overflow-hidden rounded-full bg-secondary"
        >
            <SliderRange
                class="absolute h-full data-[orientation=vertical]:w-full bg-primary"
            />
        </SliderTrack>
        <SliderThumb
            v-for="(_, key) in modelValue"
            :key="key"
            class="block h-5 w-5 rounded-full border-2 border-primary bg-background ring-offset-background transition-colors focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50"
        />
    </SliderRoot>
</template>
