<script setup lang="ts">
import { computed } from 'vue';

import { Deferred, Head } from '@inertiajs/vue3';
import { Loader2 } from 'lucide-vue-next';

import type { Anime } from '@/entities/anime';
import {
    type AnimeListEntry,
    type AnimeListStatistics,
    Status,
} from '@/entities/anime-list';
import { AnimeListEntryControl } from '@/features/anime/list-entry-control';
import { AnimeListStatistic } from '@/features/anime/list-statistic';
import { AnimeRating } from '@/features/anime/rating';
import { Badge } from '@/shared/ui/badge';
import { Block } from '@/shared/ui/block';
import { ExternalLink } from '@/shared/ui/external-link';
import { AuthenticatedLayout } from '@/widgets/layouts';

type Props = {
    anime: Anime;
    animeListEntry?: AnimeListEntry;
    animeListStatistic?: AnimeListStatistics;
    animeListStatuses: Status[];
};

const props = defineProps<Props>();

const rating = computed(() => props.anime.rating);
const links = computed(() => props.anime.urls.map((link) => link.url));
const synonyms = computed(() => props.anime.synonyms.map((synonym) => synonym.name));
const genres = computed(() => props.anime.genres.map((genre) => genre.name));
const voiceActingList = computed(() =>
    props.anime.voice_acting.map((voiceActing) => voiceActing.name)
);
</script>

<template>
    <Head>
        <title>{{ anime.title }}</title>
        <meta property="og:title" :content="anime.title" />
        <meta property="og:description" :content="anime.title" />
        <meta property="og:image" :content="anime.image.path" />
        <meta property="og:url" :content="route('anime.show', anime.id)" />
        <meta property="og:type" content="anime" />
    </Head>

    <AuthenticatedLayout>
        <article class="flex flex-col gap-4">
            <section class="relative">
                <div class="absolute inset-0">
                    <img
                        :src="anime.image.path"
                        class="w-full h-full object-cover opacity-30"
                        :alt="anime.title"
                    />
                    <div
                        class="absolute inset-0 bg-gradient-to-t from-gray-900 to-transparent"
                    ></div>
                </div>

                <div class="relative container mx-auto px-4 h-full">
                    <div class="flex items-end h-full py-4">
                        <div class="flex gap-8">
                            <div class="hidden sm:block">
                                <img
                                    :src="anime.image.path"
                                    :alt="anime.title"
                                    class="w-64 h-96 object-cover rounded-lg shadow-2xl"
                                />
                            </div>

                            <div class="flex flex-col gap-2">
                                <AnimeRating v-model="rating" />

                                <h1 class="text-4xl font-bold">{{ anime.title }}</h1>

                                <div class="space-y-1">
                                    <p
                                        v-for="(synonym, key) in synonyms"
                                        :key="key"
                                        class="text-sm"
                                    >
                                        {{ synonym }}
                                    </p>
                                </div>

                                <div class="flex flex-wrap gap-2">
                                    <Badge
                                        v-for="genre in genres"
                                        :key="genre"
                                        class="cursor-pointer"
                                    >
                                        {{ genre }}
                                    </Badge>
                                </div>

                                <Deferred
                                    :data="['animeListEntry', 'animeListStatistic']"
                                >
                                    <template #fallback>
                                        <Loader2 class="animate-spin" />
                                    </template>

                                    <AnimeListEntryControl
                                        :anime-id="anime.id"
                                        :entry="animeListEntry"
                                        :statuses="animeListStatuses"
                                    />

                                    <AnimeListStatistic
                                        :anime-list-statistic="animeListStatistic"
                                    />
                                </Deferred>

                                <div>
                                    <ExternalLink
                                        v-for="(link, key) in links"
                                        :key="key"
                                        :url="link"
                                        text="Watch"
                                        class="px-6 py-2 bg-red-600 rounded-lg hover:bg-red-700 transition inline-flex items-center gap-2"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <Block as="section" class="rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">Information</h2>

                    <dl
                        class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-x-6 gap-y-4"
                    >
                        <div class="space-y-1">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">
                                Episodes
                            </dt>
                            <dd class="text-base font-medium">{{ anime.episodes }}</dd>
                        </div>

                        <div class="space-y-1">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">
                                Status
                            </dt>
                            <dd class="text-base font-medium">{{ anime.status }}</dd>
                        </div>

                        <div class="space-y-1">
                            <dt class="text-sm text-gray-500 dark:text-gray-400">
                                Voice acting
                            </dt>
                            <dd class="text-base font-medium">
                                <div class="flex flex-wrap gap-1">
                                    <Badge
                                        v-for="voiceActing in voiceActingList"
                                        :key="voiceActing"
                                        class="cursor-pointer"
                                    >
                                        {{ voiceActing }}
                                    </Badge>
                                </div>
                            </dd>
                        </div>
                    </dl>
                </div>
            </Block>

            <Block as="section" class="rounded-xl shadow-sm overflow-hidden">
                <div class="p-6">
                    <h2 class="text-xl font-semibold mb-4">Description</h2>

                    Lorem ipsum dolor sit amet consectetur adipisicing elit.
                </div>
            </Block>
        </article>
    </AuthenticatedLayout>
</template>

<style scoped></style>
