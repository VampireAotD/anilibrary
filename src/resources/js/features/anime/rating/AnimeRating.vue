<script setup lang="ts">
import Rating from 'primevue/rating';

const rating = defineModel<number>();
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
