<script setup lang="ts">
import { BadgeInfo } from 'lucide-vue-next';

type Props = {
    message?: string;
};

defineProps<Props>();
</script>

<template>
    <div v-show="message">
        <div
            class="flex justify-start justify-items-center gap-1 text-sm text-red-600 dark:text-red-400"
        >
            <BadgeInfo />
            <span class="leading-none">{{ message }}</span>
        </div>
    </div>
</template>
