<script setup lang="ts">
import { Rating } from '@/shared/ui/rating';

const rating = defineModel<number>();
</script>

<template>
    <div class="flex gap-2" role="status" aria-live="polite">
        <span id="ratingValue">{{ rating }} / 10</span>

        <Rating v-model="rating" :max-rating="10" readonly />
    </div>
</template>

<style scoped></style>
