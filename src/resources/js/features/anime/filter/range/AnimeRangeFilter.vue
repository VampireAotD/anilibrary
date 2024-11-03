<script setup lang="ts">
import { Slider } from '@/shared/ui/slider';

type Props = {
    name: string;
    min: number;
    max: number;
};

defineProps<Props>();

const model = defineModel<number[]>();
</script>

<template>
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ name }}</label>
    <Slider v-model="model" :min="min" :max="max" />
</template>

<style scoped></style>
