<script setup lang="ts">
import { InfoIcon } from 'lucide-vue-next';

type Props = {
    message?: string;
};

defineProps<Props>();
</script>

<template>
    <div
        v-show="message"
        class="flex justify-start items-center gap-1 text-sm text-red-600 dark:text-red-400"
    >
        <InfoIcon class="w-4 h-3" />
        <span class="leading-none">{{ message }}</span>
    </div>
</template>
