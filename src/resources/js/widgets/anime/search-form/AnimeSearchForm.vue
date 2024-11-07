<script setup lang="ts">
import { reactive, ref, watch, watchEffect } from 'vue';

import { AnimeMultiselectFilter, AnimeRangeFilter } from '@/features/anime/filter';

import type { Filters, SelectedFilters } from './types';

type Props = {
    filters: Filters;
    selectedFilters: SelectedFilters;
};

const props = defineProps<Props>();
const emit = defineEmits<{ updateFilters: [filters: SelectedFilters] }>();

const { years, types, statuses, genres, voiceActing } = props.filters;
const yearsRange = ref([years?.min, years?.max]);
const data = reactive({ ...props.selectedFilters });

watchEffect(() => {
    data.years = {
        min: yearsRange.value[0],
        max: yearsRange.value[1],
    };
});

watch(data, (updated: SelectedFilters) => {
    emit('updateFilters', updated);
});
</script>

<template>
    <form class="flex flex-col bg-muted p-4 gap-4 rounded-lg shadow-lg max-h-max">
        <h2 class="text-lg font-bold">Фильтры</h2>

        <AnimeRangeFilter
            name="Год выхода"
            v-model="yearsRange"
            :min="years.min ?? 0"
            :max="years.max ?? 0"
        />

        <AnimeMultiselectFilter name="Тип" v-model="data.types" :data="types" />

        <AnimeMultiselectFilter name="Статус" v-model="data.statuses" :data="statuses" />

        <AnimeMultiselectFilter name="Жанры" v-model="data.genres" :data="genres" />

        <AnimeMultiselectFilter
            name="Озвучка"
            v-model="data.voiceActing"
            :data="voiceActing"
        />
    </form>
</template>

<style scoped></style>
