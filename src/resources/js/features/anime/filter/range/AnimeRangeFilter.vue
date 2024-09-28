<script setup lang="ts">
import { Slider } from '@/shared/ui/slider';

type Props = {
    name: string;
    min: number;
    max: number;
};

defineProps<Props>();

const model = defineModel<number[]>();
const emit = defineEmits<{ change: [number[]] }>();

defineExpose();
</script>

<template>
    <label class="block text-sm font-medium text-gray-700 mb-2">{{ name }}</label>
    <Slider
        class="w-64"
        v-model="model"
        :min="min"
        :max="max"
        range
        @change="emit('change', model)"
    />
</template>

<style scoped></style>
