<script setup lang="ts">
import { onBeforeMount, ref } from 'vue';

import { Head, useForm } from '@inertiajs/vue3';
import qs from 'qs';

import { Anime } from '@/entities/anime';
import { AnimeSearchItem } from '@/features/anime/search-item';
import { Button } from '@/shared/ui/button';
import { AnimeSearchFilters, type Filters } from '@/widgets/anime';
import { AddAnimeModal } from '@/widgets/anime/add-anime-modal';
import { AuthenticatedLayout } from '@/widgets/layouts';

type Props = {
    items: Anime[];
    filters: Filters;
};

const props = defineProps<Props>();
const optionModalVisible = ref<boolean>(false);

const form = useForm({
    page: 1,
    perPage: 20,
    filters: {
        years: {
            min: props.filters.years.min,
            max: props.filters.years.max,
        },
        types: [],
        statuses: [],
        genres: [],
        voiceActing: [],
    },
});

const search = (filters: object) => {
    form.filters = filters;

    form.get(route('anime.index'), {
        preserveState: true,
        preserveScroll: true,
        only: ['items'],
    });
};

onBeforeMount(() => {
    const queryParams = qs.parse(window.location.search, { ignoreQueryPrefix: true });

    if (queryParams.filters) {
        form.filters = { ...form.filters, ...queryParams.filters };
    }

    if (queryParams.page) {
        form.page = queryParams.page as number;
    }

    if (queryParams.perPage) {
        form.perPage = queryParams.perPage as number;
    }
});
</script>

<template>
    <Head title="Search anime" />

    <AuthenticatedLayout>
        <div class="bg-muted p-4 rounded-lg shadow">
            <Button @click="optionModalVisible = true">Add anime</Button>
        </div>

        <section class="grid grid-cols-[80%_20%] gap-2 p-4 mx-auto">
            <div class="divide-y divide-black-100 dark:divide-gray-100">
                <AnimeSearchItem v-for="anime in items" :key="anime.id" :anime="anime" />
            </div>

            <AnimeSearchFilters
                :filters="props.filters"
                :selected-filters="form.filters"
                @update-filters="search"
            />
        </section>

        <AddAnimeModal
            :visible="optionModalVisible"
            @close="optionModalVisible = false"
        />
    </AuthenticatedLayout>
</template>

<style scoped></style>
