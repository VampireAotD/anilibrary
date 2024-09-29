<script setup lang="ts">
import { ref } from 'vue';

import { Head } from '@inertiajs/vue3';

import {
    type AddedAnimePerDomain,
    type AddedAnimePerMonth,
    Anime,
} from '@/entities/anime';
import { AnimeCarousel } from '@/features/dashboard/anime-carousel';
import { Checkbox } from '@/shared/ui/checkbox';
import { Charts, StatisticCards } from '@/widgets/dashboard';
import { AuthenticatedLayout } from '@/widgets/layouts';

type Props = {
    animeCount: number;
    usersCount: number;
    animePerMonth: AddedAnimePerMonth;
    animePerDomain: AddedAnimePerDomain;
    latestAnime: Anime[];
};

defineProps<Props>();

const allo = ref([]);
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

                <Checkbox v-model="allo" :value="false" class="bg-red-100" />

                <Charts
                    :anime-per-month="animePerMonth"
                    :anime-per-domain="animePerDomain"
                />

                <AnimeCarousel :data="latestAnime" :display="4" :scroll="1" />
            </div>
        </div>
    </AuthenticatedLayout>
</template>
