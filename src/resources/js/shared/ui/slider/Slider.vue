<script setup lang="ts">
import Slider, { SliderProps } from 'primevue/slider';

type Props = SliderProps;

const props = defineProps<Props>();
const model = defineModel<number[]>();

defineExpose();
</script>

<template>
    <Slider v-bind="props" v-model="model" />
</template>

<style scoped></style>
