<script setup lang="ts">
import { CountAnimePerDomain, ParsedMonthlyAnime } from '@/features/dashboard/charts';
import { SectionTitle } from '@/shared/ui/section-title';
import { type AddedAnimePerDomain, type AddedAnimePerMonth } from '@/entities/anime';

type Props = {
    animePerMonth: AddedAnimePerMonth;
    animePerDomain: AddedAnimePerDomain;
};

defineProps<Props>();
</script>

<template>
    <div class="mb-10 md:h-96">
        <SectionTitle title="Charts" />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-5">
            <ParsedMonthlyAnime :monthly-statistic="animePerMonth" />

            <CountAnimePerDomain :domain-statistic="animePerDomain" />
        </div>
    </div>
</template>

<style scoped></style>
