<script setup lang="ts">
import { ref } from 'vue';

import { Star } from 'lucide-vue-next';

type Props = {
    maxRating?: number;
    precision?: number;
    readonly?: boolean;
};

const rating = defineModel<number>();
const { maxRating = 5, precision = 1, readonly = false } = defineProps<Props>();

const hoverRating = ref<number | null>(null);

const setRating = (value: number) => {
    if (readonly) return;

    rating.value = value;
};

const handleHover = (event: MouseEvent, iconIndex: number) => {
    if (readonly) return;

    const { offsetX, currentTarget } = event;
    const targetWidth = (currentTarget as HTMLElement).offsetWidth;
    const hoverValue =
        iconIndex - 1 + Math.ceil(offsetX / targetWidth / precision) * precision;
    hoverRating.value = parseFloat(hoverValue.toFixed(1));
};

const resetHoverRating = () => {
    if (readonly) return;

    hoverRating.value = null;
};

const iconFill = (iconIndex: number) => {
    const currentRating = hoverRating.value !== null ? hoverRating.value : rating.value;
    return Math.min(Math.max(currentRating - (iconIndex - 1), 0), 1) * 100;
};
</script>

<template>
    <div class="flex space-x-1" @mouseleave="resetHoverRating">
        <div
            v-for="i in maxRating"
            :key="i"
            class="relative cursor-pointer"
            @mousemove="handleHover($event, i)"
            @click="setRating(i)"
        >
            <slot name="empty-icon">
                <Star class="w-6 h-6 text-gray-400" fill="currentColor" stroke="none" />
            </slot>

            <div
                class="absolute top-0 left-0 w-6 h-6 overflow-hidden"
                :style="{ width: `${iconFill(i)}%` }"
            >
                <slot name="filled-icon">
                    <Star
                        class="w-6 h-6 text-yellow-400"
                        fill="currentColor"
                        stroke="none"
                    />
                </slot>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
