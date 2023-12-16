<script setup lang="ts">
import { AuthenticatedLayout } from '@/widgets/layouts';
import { Head } from '@inertiajs/vue3';
import { AnimePerDomain } from '@/entities/anime';
import { Charts, StatisticCards } from '@/widgets/dashboard';
import { AnimeCarousel } from '@/features/dashboard/anime-carousel';
import { provide } from 'vue';
import { Models } from '@/types';

type Props = {
    animeCount: number;
    usersCount: number;
    animePerMonth: number[];
    animePerDomain: AnimePerDomain;
    latestAnime: Models.Anime[];
};

const props = defineProps<Props>();

provide('animePerMonth', props.animePerMonth);
provide('animePerDomain', props.animePerDomain);
</script>

<template>
    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Dashboard
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <StatisticCards :anime-count="animeCount" :users-count="usersCount" />

                <Charts
                    :anime-per-month="animePerMonth"
                    :anime-per-domain="animePerDomain"
                />

                <AnimeCarousel :data="latestAnime" :display="4" :scroll="1" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
