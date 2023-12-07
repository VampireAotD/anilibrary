<script setup lang="ts">
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import ExternalLink from '@/Components/BaseExternalLink.vue';
import DangerButton from '@/Components/Button/DangerButton.vue';
import { Models } from '@/types';

const props = defineProps<{
    anime: Models.Anime;
}>();

const links = computed(() => props.anime.urls.map((link) => link.url));
const synonyms = computed(() => props.anime.synonyms.map((synonym) => synonym.synonym));
const genres = computed(() => props.anime.genres.map((genre) => genre.name));
const voiceActingList = computed(() =>
    props.anime.voice_acting.map((voiceActing) => voiceActing.name)
);
</script>

<template>
    <Head :title="anime.title" />

    <AuthenticatedLayout>
        <template #header>
            <h2
                class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight"
            >
                Details for {{ anime.title }} anime
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <section class="p-6 text-gray-600 body-font">
                        <div class="container px-5 mx-auto flex flex-wrap">
                            <div
                                class="lg:w-1/4 h-1/3 mb-10 lg:mb-0 rounded-lg overflow-hidden"
                            >
                                <img
                                    :src="anime.image.path"
                                    class="object-cover object-center h-full w-full"
                                    :alt="anime.title"
                                />
                            </div>

                            <div
                                class="flex flex-col flex-wrap h-full lg:w-1/2 lg:pl-12 lg:text-left text-center"
                            >
                                <div class="flex flex-col lg:items-start items-center">
                                    <div class="flex-grow">
                                        <div class="mb-2">
                                            <h2
                                                class="text-gray-900 text-lg title-font font-medium mb-1 font-semibold dark:text-gray-200 leading-tight"
                                            >
                                                {{ anime.title }}
                                            </h2>

                                            <ul class="list-none">
                                                <li
                                                    v-for="(synonym, key) in synonyms"
                                                    :key="key"
                                                    class="font-medium text-sm text-neutral-300 font-medium"
                                                >
                                                    {{ synonym }}
                                                </li>
                                            </ul>
                                        </div>

                                        <div class="mb-2">
                                            <h3
                                                class="text-gray-400 text-md font-medium font-semibold dark:text-gray-200 leading-tight"
                                            >
                                                Links
                                            </h3>

                                            <div class="flex flex-wrap">
                                                <ExternalLink
                                                    v-for="(link, key) in links"
                                                    :key="key"
                                                    :url="link"
                                                    class="text-neutral-400 hover:underline mr-1"
                                                    text="Watch"
                                                />
                                            </div>
                                        </div>

                                        <div class="mb-2">
                                            <h3
                                                class="text-gray-400 text-md font-medium font-semibold dark:text-gray-200 leading-tight"
                                            >
                                                Genres
                                            </h3>

                                            <div class="flex flex-wrap">
                                                <span
                                                    v-for="(genre, key) in genres"
                                                    :key="key"
                                                    class="cursor-pointer text-neutral-400 hover:underline mr-1"
                                                >
                                                    {{ genre }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="mb-4">
                                            <h3
                                                class="text-gray-400 text-md font-medium font-semibold dark:text-gray-200 leading-tight"
                                            >
                                                Voice acting
                                            </h3>

                                            <div class="flex flex-wrap">
                                                <span
                                                    v-for="(
                                                        voiceActing, key
                                                    ) in voiceActingList"
                                                    :key="key"
                                                    class="cursor-pointer text-neutral-400 hover:underline mr-1"
                                                >
                                                    {{ voiceActing }}
                                                </span>
                                            </div>
                                        </div>

                                        <div class="flex flex-wrap">
                                            <DangerButton>Delete</DangerButton>
                                        </div>
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
