<script setup lang="ts">
import type { Anime } from '@/entities/anime';
import { AnimeSearchItem } from '@/features/anime/search-item';

type Props = {
    items?: Anime[];
};

defineProps<Props>();
</script>

<template>
    <section v-if="items?.length" class="flex flex-col gap-2">
        <AnimeSearchItem v-for="anime in items" :anime="anime" :key="anime.id" />
    </section>

    <p v-else class="p-2 text-center">No anime available</p>
</template>

<style scoped></style>
