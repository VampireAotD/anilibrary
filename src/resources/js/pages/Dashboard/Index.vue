<script setup lang="ts">
import { Deferred, Head } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';

import { type Anime } from '@/entities/anime';
import { AnimeCarousel, AnimeList, SiteDescription } from '@/widgets/dashboard';
import { AuthenticatedLayout } from '@/widgets/layouts';

type Props = {
    latestAnime: Anime[];
    completedAnime?: Anime[];
    mostPopularAnime?: Anime[];
};

defineProps<Props>();
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <section class="flex flex-col gap-4">
            <SiteDescription />

            <AnimeCarousel :data="latestAnime" />

            <Deferred :data="['completedAnime', 'mostPopularAnime']">
                <template #fallback>
                    <Loader2 class="animate-spin" />
                </template>

                <section class="grid sm:grid-cols-2 gap-4">
                    <AnimeList title="Completed" :data="completedAnime" />
                    <AnimeList title="Most popular" :data="mostPopularAnime" />
                </section>
            </Deferred>
        </section>
    </AuthenticatedLayout>
</template>
