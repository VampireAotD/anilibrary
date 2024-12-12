<script setup lang="ts">
import { computed } from 'vue';

import { Link } from '@inertiajs/vue3';

import { Anime } from '@/entities/anime';

type Props = {
    anime: Anime;
};

const props = defineProps<Props>();

const firstSynonym = computed(() => props.anime.synonyms[0]?.name);
const genres = computed(() => props.anime.genres.map((genre) => genre.name).join(', '));
</script>

<template>
    <article class="flex justify-between px-4 shadow-md">
        <div class="flex min-w-0 gap-x-4">
            <img
                class="w-32 flex-none bg-gray-50"
                :src="anime.image?.path"
                :alt="anime.title"
            />

            <div class="min-w-0 flex-auto">
                <Link
                    :href="route('anime.show', anime.id)"
                    target="_blank"
                    class="text-sm text-wrap font-semibold leading-6"
                >
                    {{ anime.title }}
                </Link>
                <p class="truncate text-xs leading-5">{{ firstSynonym }}</p>
                <p class="mt-1 truncate text-xs leading-4 text-wrap">
                    {{ anime.year }} / {{ anime.type }} / {{ anime.status }} /
                    {{ genres }}
                </p>
            </div>
        </div>
    </article>
</template>

<style scoped></style>
