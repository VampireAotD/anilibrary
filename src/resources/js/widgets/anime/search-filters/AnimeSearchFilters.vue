<script setup lang="ts">
import { reactive, ref, watch, watchEffect } from 'vue';

import { AnimeCheckboxFilter, AnimeRangeFilter } from '@/features/anime/filter';

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

watch(data, (updated) => {
    emit('updateFilters', updated);
});
</script>

<template>
    <div class="bg-muted p-4 rounded-lg shadow-lg">
        <h2 class="text-lg font-bold mb-4">Фильтры</h2>

        <div class="mb-3">
            <AnimeRangeFilter
                name="Год выхода"
                v-model="yearsRange"
                :min="years.min ?? 0"
                :max="years.max ?? 0"
            />

            <div class="flex justify-between text-sm text-gray-600">
                <span>{{ yearsRange[0] }}</span>
                <span>{{ yearsRange[1] }}</span>
            </div>
        </div>

        <div class="mb-3">
            <AnimeCheckboxFilter name="Тип" v-model="data.types" :data="types" />
        </div>

        <div class="mb-3">
            <AnimeCheckboxFilter name="Статус" v-model="data.statuses" :data="statuses" />
        </div>

        <div class="mb-3">
            <AnimeCheckboxFilter name="Жанры" v-model="data.genres" :data="genres" />
        </div>

        <div class="mb-3">
            <AnimeCheckboxFilter
                name="Озвучка"
                v-model="data.voiceActing"
                :data="voiceActing"
            />
        </div>
    </div>
</template>

<style scoped></style>
