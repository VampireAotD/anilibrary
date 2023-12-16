<script setup lang="ts">
import { Chart } from '@/shared/ui/chart';
import { computed, inject } from 'vue';
import { type AnimePerDomain } from '@/entities/anime';

const animePerDomain = inject<AnimePerDomain>('animePerDomain') as object;

const data = computed(() => ({
    labels: Object.keys(animePerDomain),
    datasets: [
        {
            label: 'Anime per domain',
            data: Object.values(animePerDomain),
            backgroundColor: [
                'rgba(255, 159, 64, 0.2)',
                'rgba(75, 192, 192, 0.2)',
                'rgba(54, 162, 235, 0.2)',
                'rgba(153, 102, 255, 0.2)',
            ],
            borderColor: [
                'rgb(255, 159, 64)',
                'rgb(75, 192, 192)',
                'rgb(54, 162, 235)',
                'rgb(153, 102, 255)',
            ],
            borderWidth: 1,
        },
    ],
}));
</script>

<template>
    <Chart type="bar" :data="data" />
</template>

<style scoped></style>
