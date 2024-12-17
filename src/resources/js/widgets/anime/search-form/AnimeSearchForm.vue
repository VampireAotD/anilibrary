<script setup lang="ts">
import { reactive, ref, watch, watchEffect } from 'vue';

import { AnimeMultiselectFilter, AnimeRangeFilter } from '@/features/anime/filter';
import { Block } from '@/shared/ui/block';

import type { Filters, SelectedFilters } from './types';

type Props = {
    title?: string;
    filters?: Filters;
    selectedFilters?: SelectedFilters;
};

const props = defineProps<Props>();
const emit = defineEmits<{ updateFilters: [filters: SelectedFilters] }>();

const yearsRange = ref([props.filters?.years?.min, props.filters?.years?.max]);
const data = reactive({ ...props.selectedFilters });

watchEffect(() => {
    data.years = {
        min: yearsRange?.value[0],
        max: yearsRange?.value[1],
    };
});

watch(data, (updated: SelectedFilters) => {
    emit('updateFilters', updated);
});
</script>

<template>
    <Block v-if="filters?.length" class="max-h-max">
        <form class="flex flex-col gap-4">
            <h2 v-if="title" class="text-lg font-bold">{{ title }}</h2>

            <AnimeRangeFilter
                name="Год выхода"
                v-model="yearsRange"
                :min="props.filters?.years?.min ?? 0"
                :max="props.filters?.years?.max ?? 0"
            />

            <AnimeMultiselectFilter
                name="Тип"
                v-model="data.types"
                :data="props.filters?.types"
            />

            <AnimeMultiselectFilter
                name="Статус"
                v-model="data.statuses"
                :data="props.filters?.statuses"
            />

            <AnimeMultiselectFilter
                name="Жанры"
                v-model="data.genres"
                :data="props.filters?.genres"
            />

            <AnimeMultiselectFilter
                name="Озвучка"
                v-model="data.voiceActing"
                :data="props.filters?.voiceActing"
            />
        </form>
    </Block>

    <p v-else class="p-2 text-center">Filters are unavailable</p>
</template>

<style scoped></style>
