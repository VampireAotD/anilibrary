<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import { type Anime } from '@/entities/anime';
import { AnimeCarousel } from '@/widgets/dashboard/anime-carousel';
import { AnimeList } from '@/widgets/dashboard/anime-list';
import { AuthenticatedLayout } from '@/widgets/layouts';

type Props = {
    latestAnime: Anime[];
    completedAnime: Anime[];
    mostPopularAnime: Anime[];
};

defineProps<Props>();
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <section class="flex flex-col gap-4">
            <AnimeCarousel :data="latestAnime" />

            <section class="grid sm:grid-cols-2 gap-4">
                <AnimeList title="Completed" :data="completedAnime" />
                <AnimeList title="Most popular" :data="mostPopularAnime" />
            </section>
        </section>
    </AuthenticatedLayout>
</template>
