<script setup lang="ts">
import { AuthenticatedLayout } from '@/widgets/layouts';
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import { Models } from '@/types';
import { ExternalLink } from '@/shared/ui/external-link';
import { AnimeRating } from '@/features/anime/rating';

const props = defineProps<{ anime: Models.Anime }>();

const rating = computed(() => props.anime.rating);
const links = computed(() => props.anime.urls.map((link) => link.url));
const synonyms = computed(() => props.anime.synonyms.map((synonym) => synonym.synonym));
const genres = computed((): string =>
    props.anime.genres.map((genre) => genre.name).join(', ')
);
const voiceActingList = computed((): string =>
    props.anime.voice_acting.map((voiceActing) => voiceActing.name).join(', ')
);
</script>

<template>
    <Head :title="anime.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                {{ anime.title }}
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm">
                    <section class="p-2 text-gray-600 body-font">
                        <div class="container mx-auto flex flex-wrap">
                            <div class="grid grid-cols-1 mb-5">
                                <div class="px-1 mb-5 lg:mb-0 overflow-hidden">
                                    <img
                                        :src="anime.image.path"
                                        class="w-full sm:w-64 sm:h-80"
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
                                            class="text-gray-900 text-4xl title-font font-medium mb-1 font-semibold dark:text-gray-200 leading-tight"
                                        >
                                            {{ anime.title }}
                                        </h2>

                                        <ul class="list-none text-xs">
                                            <li
                                                v-for="(synonym, key) in synonyms"
                                                :key="key"
                                            >
                                                {{ synonym }}
                                            </li>
                                        </ul>
                                    </div>

                                    <hr class="my-4 border-t border-gray-200" />

                                    <div>
                                        <dl class="grid grid-cols-2 gap-x-4 gap-y-2">
                                            <dt class="text-sm font-medium text-gray-500">
                                                Эпизоды
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                {{ anime.episodes }}
                                            </dd>
                                            <dt class="text-sm font-medium text-gray-500">
                                                Статус
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                {{ anime.status }}
                                            </dd>
                                            <dt class="text-sm font-medium text-gray-500">
                                                Жанр
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                {{ genres }}
                                            </dd>
                                            <dt class="text-sm font-medium text-gray-500">
                                                Озвучка
                                            </dt>
                                            <dd class="mt-1 text-sm text-gray-900">
                                                {{ voiceActingList }}
                                            </dd>
                                        </dl>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </section>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
