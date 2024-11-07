<script setup lang="ts">
import { type Anime } from '@/entities/anime';

type Props = {
    anime: Anime;
};

defineProps<Props>();
</script>

<template>
    <div class="flex items-center py-4 space-x-4">
        <img
            class="w-12 h-16 rounded"
            loading="lazy"
            :src="anime.image.path"
            :alt="anime.title"
        />

        <div class="flex flex-col">
            <h3 class="max-w-96 font-semibold truncate">
                {{ anime.title }}
            </h3>

            <span class="text-sm">{{ anime.year }}</span>
        </div>
    </div>
</template>

<style scoped></style>
