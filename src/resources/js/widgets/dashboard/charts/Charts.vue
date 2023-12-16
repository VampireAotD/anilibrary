<script setup lang="ts">
import ParsedMonthly from '@/features/dashboard/charts/parsed-monthly-anime/ParsedMonthlyAnime.vue';
import LeftBorderedHeader from '@/shared/ui/header/LeftBorderedHeader.vue';
import CountPerDomain from '@/features/dashboard/charts/count-anime-per-domain/CountAnimePerDomain.vue';
</script>

<template>
    <div class="mb-10 md:h-96">
        <LeftBorderedHeader title="Charts" />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-5">
            <ParsedMonthly />

            <CountPerDomain />
        </div>
    </div>
</template>

<style scoped></style>
