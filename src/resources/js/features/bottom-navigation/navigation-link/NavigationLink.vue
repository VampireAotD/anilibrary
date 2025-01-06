<script setup lang="ts">
import { type InertiaLinkProps, Link } from '@inertiajs/vue3';

type Props = InertiaLinkProps & {
    active?: boolean;
};

const props = defineProps<Props>();
</script>

<template>
    <Link
        v-bind="props"
        class="inline-flex items-center justify-center cursor-pointer text-center"
    >
        <slot />
    </Link>
</template>

<style scoped></style>
