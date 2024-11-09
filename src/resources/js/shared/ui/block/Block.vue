<script setup lang="ts">
import { cn } from '@/shared/helpers/tailwind';

type Props = {
    as?: 'div' | 'section';
};

const { as = 'div' } = defineProps<Props>();
</script>

<template>
    <component :is="as" :class="cn(`bg-muted p-4 rounded-lg shadow`)">
        <slot />
    </component>
</template>
