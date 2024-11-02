<script setup lang="ts">
import { reactive, ref, watchEffect } from 'vue';

import type { CountFilter, RangeFilter } from '@/entities/search';
import { AnimeCheckboxFilter, AnimeRangeFilter } from '@/features/anime/filter';

type Filters = {
    years: RangeFilter;
    types: CountFilter;
    statuses: CountFilter;
    genres: CountFilter;
    voiceActing: CountFilter;
};

type Props = {
    filters: Filters;
    selectedFilters: {
        years: {
            min: number;
            max: number;
        };
        types: string[];
        statuses: string[];
        genres: string[];
        voiceActing: string[];
    };
};

const props = defineProps<Props>();
const emit = defineEmits<{ updateFilters: Filters }>();

const { years, types, statuses, genres, voiceActing } = props.filters;

const data = reactive({ ...props.selectedFilters });
const yearsRange = ref([years.min, years.max]);

watchEffect(() => {
    data.years = {
        min: yearsRange.value[0],
        max: yearsRange.value[1],
    };
});
</script>

<template>
    <div class="bg-gray-100 dark:bg-zinc-900 p-4">
        <h2 class="text-lg font-bold mb-4">Фильтры</h2>

        <div class="mb-3">
            <AnimeRangeFilter
                name="Год выхода"
                v-model="yearsRange"
                :min="years.min"
                :max="years.max"
                @change="emit('updateFilters', $event)"
            />

            <div class="flex justify-between text-sm text-gray-600">
                <span>{{ yearsRange[0] }}</span>
                <span>{{ yearsRange[1] }}</span>
            </div>
        </div>

        <div class="mb-3">
            <AnimeCheckboxFilter
                name="Тип"
                v-model="data.types"
                :data="types"
                @change="emit('updateFilters', data)"
            />
        </div>

        <div class="mb-3">
            <AnimeCheckboxFilter
                name="Статус"
                v-model="data.statuses"
                :data="statuses"
                @change="emit('updateFilters', data)"
            />
        </div>

        <div class="mb-3">
            <AnimeCheckboxFilter
                name="Жанры"
                v-model="data.genres"
                :data="genres"
                @change="emit('updateFilters', data)"
            />
        </div>

        <div class="mb-3">
            <AnimeCheckboxFilter
                name="Озвучка"
                v-model="data.voiceActing"
                :data="voiceActing"
                @change="emit('updateFilters', data)"
            />
        </div>
    </div>
</template>

<style scoped></style>
