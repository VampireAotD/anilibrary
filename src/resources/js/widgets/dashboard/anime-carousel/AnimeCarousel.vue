<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

import { Anime } from '@/entities/anime';
import { Carousel, CarouselContent, CarouselItem } from '@/shared/ui/carousel';
import { SectionTitle } from '@/shared/ui/section-title';

type Props = {
    data: Anime[];
};

defineProps<Props>();
</script>

<template>
    <SectionTitle title="Recently added anime" class="mb-5" />

    <Carousel class="bg-white dark:bg-transparent px-4 pt-2">
        <CarouselContent>
            <CarouselItem
                v-for="anime in data"
                :key="anime.id"
                class="sm:basis-1/3 lg:basis-1/6"
            >
                <div>
                    <Link :href="route('anime.show', anime.id)">
                        <img
                            class="w-full h-72 rounded-lg object-fit"
                            :src="anime.image.path"
                            :alt="anime.title"
                        />
                    </Link>

                    <div class="px-4 py-2">
                        <Link :href="route('anime.show', anime.id)">
                            <h4
                                class="text-gray-800 dark:text-white font-bold text-center text-lg mb-1 truncate"
                            >
                                {{ anime.title }}
                            </h4>
                        </Link>
                    </div>
                </div>
            </CarouselItem>
        </CarouselContent>
    </Carousel>
</template>

<style scoped></style>
