<script setup lang="ts">
import { cn } from '@/shared/helpers/tailwind';

type Props = {
    url: string;
    text: string;
};

defineProps<Props>();
</script>

<template>
    <a
        :href="url"
        target="_blank"
        :class="
            cn(
                'border-b-2 border-transparent text-sm font-medium leading-5 focus:outline-none transition duration-150 ease-in-out'
            )
        "
    >
        {{ text }}
    </a>
</template>

<style scoped></style>
