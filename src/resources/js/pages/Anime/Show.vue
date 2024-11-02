<script setup lang="ts">
import { computed } from 'vue';

import { Head } from '@inertiajs/vue3';

import { Anime } from '@/entities/anime';
import { AnimeRating } from '@/features/anime/rating';
import { ExternalLink } from '@/shared/ui/external-link';
import { AuthenticatedLayout } from '@/widgets/layouts';

type Props = {
    anime: Anime;
};

const props = defineProps<Props>();

const rating = computed(() => props.anime.rating);
const links = computed(() => props.anime.urls.map((link) => link.url));
const synonyms = computed(() => props.anime.synonyms.map((synonym) => synonym.name));
const genres = computed(() => props.anime.genres.map((genre) => genre.name).join(', '));
const voiceActingList = computed(() =>
    props.anime.voice_acting.map((voiceActing) => voiceActing.name).join(', ')
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
        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
            <section class="p-2 body-font">
                <div class="container mx-auto flex flex-wrap">
                    <div class="grid grid-cols-1 mb-5">
                        <div class="px-1 mb-5 lg:mb-0 overflow-hidden">
                            <img
                                class="w-full sm:w-64 sm:h-80"
                                :src="anime.image.path"
                                :alt="anime.title"
                            />
                        </div>

                        <div>
                            <ExternalLink
                                v-for="(link, key) in links"
                                :key="key"
                                text="Смотреть"
                                :url="link"
                            />
                        </div>
                    </div>

                    <div
                        class="flex flex-col flex-wrap h-full lg:w-1/2 lg:pl-12 lg:text-left"
                    >
                        <div class="flex flex-col">
                            <AnimeRating v-model="rating" class="mb-2" />

                            <div class="mb-2">
                                <h2
                                    class="text-4xl title-font font-medium mb-1 font-semibold leading-tight"
                                >
                                    {{ anime.title }}
                                </h2>

                                <ul class="list-none text-xs">
                                    <li v-for="(synonym, key) in synonyms" :key="key">
                                        {{ synonym }}
                                    </li>
                                </ul>
                            </div>

                            <hr class="my-4 border-t border-gray-200" />

                            <div>
                                <dl class="grid grid-cols-2 gap-x-4 gap-y-2">
                                    <dt class="text-sm font-medium">Эпизоды</dt>
                                    <dd class="mt-1 text-sm">
                                        {{ anime.episodes }}
                                    </dd>
                                    <dt class="text-sm font-medium">Статус</dt>
                                    <dd class="mt-1 text-sm">
                                        {{ anime.status }}
                                    </dd>
                                    <dt class="text-sm font-medium">Жанр</dt>
                                    <dd class="mt-1 text-sm">
                                        {{ genres }}
                                    </dd>
                                    <dt class="text-sm font-medium">Озвучка</dt>
                                    <dd class="mt-1 text-sm">
                                        {{ voiceActingList }}
                                    </dd>
                                </dl>
                            </div>
                        </div>
                    </div>
                </div>
            </section>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
