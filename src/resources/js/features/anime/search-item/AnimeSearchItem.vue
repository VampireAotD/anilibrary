<script setup lang="ts">
import { Models } from '@/types';
import { computed } from 'vue';
import { Link } from '@inertiajs/vue3';

type Props = {
    anime: Models.Anime;
};

const props = defineProps<Props>();

const firstSynonym = computed(() => props.anime.synonyms[0]?.name);
const genres = computed(() => props.anime.genres.map((genre) => genre.name).join(', '));
</script>

<template>
    <div class="flex justify-between gap-x-6 py-5">
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
                    class="text-sm font-semibold leading-6 text-gray-900"
                >
                    {{ anime.title }}
                </Link>
                <p class="truncate text-xs leading-5 text-gray-700">{{ firstSynonym }}</p>
                <p class="mt-1 truncate text-xs leading-4 text-gray-500">
                    {{ anime.year }} / {{ anime.type }} / {{ anime.status }} /
                    {{ genres }}
                </p>
            </div>
        </div>
    </div>
</template>

<style scoped></style>
