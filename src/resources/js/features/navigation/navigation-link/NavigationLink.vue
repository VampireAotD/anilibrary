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
        class="inline-flex justify-center items-center size-8 cursor-pointer rounded-lg ease-in-out"
        :class="
            active
                ? `bg-black dark:bg-gray-100 text-white dark:text-black`
                : `hover:bg-gray-200 dark:hover:bg-gray-700`
        "
    >
        <slot />
    </Link>
</template>

<style scoped></style>
