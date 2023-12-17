<script setup lang="ts">
import { CountAnimePerDomain, ParsedMonthlyAnime } from '@/features/dashboard/charts';
import { SectionTitle } from '@/shared/ui/section-title';
</script>

<template>
    <div class="mb-10 md:h-96">
        <SectionTitle title="Charts" />

        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 mt-5">
            <ParsedMonthlyAnime />

            <CountAnimePerDomain />
        </div>
    </div>
</template>

<style scoped></style>
