<script setup lang="ts">
import { type AddedAnimePerMonth } from '@/entities/anime';
import { Chart } from '@/shared/ui/chart';

type Props = {
    monthlyStatistic: AddedAnimePerMonth;
};

const { monthlyStatistic } = defineProps<Props>();

const months: string[] = [
    'January',
    'February',
    'March',
    'April',
    'May',
    'June',
    'July',
    'August',
    'September',
    'October',
    'November',
    'December',
];

const documentStyle = getComputedStyle(document.documentElement);

const data = {
    labels: months,
    datasets: [
        {
            label: 'Parsed anime per month',
            data: monthlyStatistic,
            fill: false,
            borderColor: documentStyle.getPropertyValue('--red-500'),
            tension: 0.4,
        },
    ],
};
</script>

<template>
    <Chart type="line" :data="data" />
</template>

<style scoped></style>
