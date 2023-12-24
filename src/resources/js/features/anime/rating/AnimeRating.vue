<script setup lang="ts">
import Rating from 'primevue/rating';
import { computed } from 'vue';

type Props = {
    modelValue: number;
};

const props = defineProps<Props>();
const emit = defineEmits<{ 'update:modelValue': [value: number] }>();

const rating = computed({
    get: () => props.modelValue,
    set: (value: number) => emit('update:modelValue', value),
});
</script>

<template>
    <div class="flex gap-2" role="status" aria-live="polite">
        <span id="ratingValue">{{ rating }} / 10</span>
        <Rating
            v-model="rating"
            readonly
            :stars="10"
            :cancel="false"
            aria-labelledby="ratingValue"
        />
    </div>
</template>

<style scoped></style>
