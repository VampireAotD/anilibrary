<script setup lang="ts">
import Chart from 'primevue/chart';

type Props = {
    type: string;
    data: object;
};

defineProps<Props>();
</script>

<template>
    <Chart
        :type="type"
        :data="data"
        class="bg-white dark:bg-gray-800 overflow-hidden shadow sm:rounded-lg"
    />
</template>

<style scoped></style>
