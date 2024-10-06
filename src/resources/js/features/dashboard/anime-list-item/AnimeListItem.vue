<script setup lang="ts">
import { type Anime } from '@/entities/anime';

type Props = {
    anime: Anime;
};

defineProps<Props>();
</script>

<template>
    <div class="flex items-center py-4 space-x-4">
        <img :src="anime.image.path" :alt="anime.title" class="w-12 h-16 rounded" />

        <div class="flex flex-col">
            <div class="flex items-center">
                <h3 class="max-w-72 text-gray-900 dark:text-white font-semibold truncate">
                    {{ anime.title }}
                </h3>
            </div>

            <span class="text-gray-900 dark:text-white text-sm">{{ anime.year }}</span>
        </div>
    </div>
</template>

<style scoped></style>
