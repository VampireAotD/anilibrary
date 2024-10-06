<script setup lang="ts">
import { type Anime } from '@/entities/anime';
import { AnimeListItem } from '@/features/dashboard/anime-list-item';
import { SectionTitle } from '@/shared/ui/section-title';

type Props = {
    title: string;
    data: Anime[];
};

defineProps<Props>();
</script>

<template>
    <section>
        <SectionTitle :title="title" />

        <div class="flex flex-col divide-y divide-black dark:divide-gray-300">
            <AnimeListItem v-for="anime in data" :key="anime.id" :anime="anime" />
        </div>
    </section>
</template>

<style scoped></style>
