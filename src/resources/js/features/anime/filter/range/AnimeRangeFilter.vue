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
    <fieldset>
        <legend class="mb-2">{{ name }}</legend>

        <Slider v-model="model" :min="min" :max="max" />

        <div class="flex justify-between text-sm mt-2">
            <span>{{ model[0] }}</span>
            <span>{{ model[1] }}</span>
        </div>
    </fieldset>
</template>

<style scoped></style>
