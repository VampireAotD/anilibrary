<script setup lang="ts">
import { Chart } from '@/shared/ui/chart';
import { computed } from 'vue';
import { type AddedAnimePerDomain } from '@/entities/anime';

type Props = {
    domainStatistic: AddedAnimePerDomain;
};

const { domainStatistic } = defineProps<Props>();

const data = computed(() => ({
    labels: Object.keys(domainStatistic),
    datasets: [
        {
            label: 'Anime per domain',
            data: Object.values(domainStatistic),
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
