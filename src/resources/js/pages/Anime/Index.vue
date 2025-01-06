<script setup lang="ts">
import { onBeforeMount, ref } from 'vue';

import { Deferred, Head, useForm } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';
import qs from 'qs';

import type { Anime } from '@/entities/anime';
import { Block } from '@/shared/ui/block';
import { Button } from '@/shared/ui/button';
import {
    Sheet,
    SheetContent,
    SheetDescription,
    SheetHeader,
    SheetTitle,
    SheetTrigger,
} from '@/shared/ui/sheet';
import { AnimeSearchForm, type Filters } from '@/widgets/anime';
import { AddAnimeModal } from '@/widgets/anime/add-anime-modal';
import { AnimeSearchItems } from '@/widgets/anime/search-items';
import { AuthenticatedLayout } from '@/widgets/layouts';

type Props = {
    items?: Anime[];
    filters?: Filters;
};

const props = defineProps<Props>();
const optionModalVisible = ref<boolean>(false);

const form = useForm({
    page: 1,
    perPage: 20,
    filters: {
        years: {
            min: props.filters?.years?.min,
            max: props.filters?.years?.max,
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
        <Block as="section" class="flex gap-2">
            <Button v-if="hasRole('owner')" @click="optionModalVisible = true">
                Add anime
            </Button>

            <Sheet>
                <SheetTrigger as-child>
                    <Button class="lg:hidden">Filters</Button>
                </SheetTrigger>
                <SheetContent>
                    <SheetHeader>
                        <SheetTitle> Anime search filters</SheetTitle>
                        <SheetDescription> Find your desired anime</SheetDescription>
                    </SheetHeader>

                    <Deferred data="filters">
                        <template #fallback>
                            <Loader2 class="animate-spin" />
                        </template>

                        <AnimeSearchForm
                            class="bg-transparent p-0 lg:hidden"
                            :filters="props.filters"
                            :selected-filters="form.filters"
                            @update-filters="search"
                        />
                    </Deferred>
                </SheetContent>
            </Sheet>
        </Block>

        <section class="grid lg:grid-cols-[8fr_2fr] items-center mt-2 text-center">
            <Deferred :data="['items', 'filters']">
                <template #fallback>
                    <Loader2 class="animate-spin" />
                </template>

                <AnimeSearchItems :items="items" />

                <AnimeSearchForm
                    class="hidden lg:block"
                    :filters="props.filters"
                    :selected-filters="form.filters"
                    @update-filters="search"
                />
            </Deferred>
        </section>

        <AddAnimeModal
            :visible="optionModalVisible"
            @close="optionModalVisible = false"
        />
    </AuthenticatedLayout>
</template>

<style scoped></style>
