<script setup lang="ts">
import { ref, watchEffect } from 'vue';

import Button from 'primevue/button';

import { Head, useForm } from '@inertiajs/vue3';

import { Anime } from '@/entities/anime';
import { AnimeCheckboxFilter, AnimeRangeFilter } from '@/features/anime/filter';
import { AnimeSearchItem } from '@/features/anime/search-item';
import { AddAnimeOptionModal } from '@/widgets/anime/add-anime-option-modal';
import { AuthenticatedLayout } from '@/widgets/layouts';

type Props = {
    items: Anime[];
    filters: {
        years: {
            min: number;
            max: number;
        };
        types: Record<string, number>;
        statuses: Record<string, number>;
        genres: Record<string, number>;
        voiceActing: Record<string, number>;
    };
};

const props = defineProps<Props>();
const { years, types, statuses, genres, voiceActing } = props.filters;
const optionModalVisible = ref<boolean>(false);

const form = useForm({
    page: 1,
    perPage: 20,
    filters: {
        years: {
            min: years.min,
            max: years.max,
        },
        types: [],
        statuses: [],
        genres: [],
        voiceActing: [],
    },
});

const yearsRange = ref([years.min, years.max]);

watchEffect(() => {
    form.filters.years = {
        min: yearsRange.value[0],
        max: yearsRange.value[1],
    };
});

const search = () => {
    form.get(route('anime.index'), {
        preserveState: true,
        preserveScroll: true,
        only: ['items'],
    });
};
</script>

<template>
    <Head title="Search anime" />

    <AuthenticatedLayout>
        <div class="bg-white dark:bg-zinc-700 shadow mb-2 p-2">
            <Button
                label="Add anime"
                icon="pi pi-plus"
                severity="success"
                @click="optionModalVisible = true"
            />
        </div>

        <section class="grid grid-cols-[80%_20%] gap-2 p-6 mx-auto">
            <div class="divide-y divide-black-100 dark:divide-gray-100">
                <AnimeSearchItem v-for="anime in items" :key="anime.id" :anime="anime" />
            </div>

            <div class="bg-gray-100 dark:bg-zinc-900 p-4">
                <h2 class="text-lg font-bold mb-4">Фильтры</h2>

                <div class="mb-3">
                    <AnimeRangeFilter
                        name="Год выхода"
                        v-model="yearsRange"
                        :min="years.min"
                        :max="years.max"
                        @change="search"
                    />

                    <div class="flex justify-between text-sm text-gray-600">
                        <span>{{ form.filters.years.min }}</span>
                        <span>{{ form.filters.years.max }}</span>
                    </div>
                </div>

                <div class="mb-3">
                    <AnimeCheckboxFilter
                        name="Тип"
                        v-model="form.filters.types"
                        :data="types"
                        @change="search"
                    />
                </div>

                <div class="mb-3">
                    <AnimeCheckboxFilter
                        name="Статус"
                        v-model="form.filters.statuses"
                        :data="statuses"
                        @change="search"
                    />
                </div>

                <div class="mb-3">
                    <AnimeCheckboxFilter
                        name="Жанры"
                        v-model="form.filters.genres"
                        :data="genres"
                        @change="search"
                    />
                </div>

                <div class="mb-3">
                    <AnimeCheckboxFilter
                        name="Озвучка"
                        v-model="form.filters.voiceActing"
                        :data="voiceActing"
                        @change="search"
                    />
                </div>
            </div>
        </section>

        <AddAnimeOptionModal
            :visible="optionModalVisible"
            @close="optionModalVisible = false"
        />
    </AuthenticatedLayout>
</template>

<style scoped></style>
