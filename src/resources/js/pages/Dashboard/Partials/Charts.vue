<script setup lang="ts">
import ParsedMonthly from '@/pages/Dashboard/Partials/ParsedMonthly.vue';
import LeftBorderedHeader from '@/shared/ui/header/LeftBorderedHeader.vue';
import CountPerDomain from '@/pages/Dashboard/Partials/CountPerDomain.vue';
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
