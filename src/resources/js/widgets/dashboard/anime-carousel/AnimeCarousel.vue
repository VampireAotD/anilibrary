<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

import { Anime } from '@/entities/anime';
import { Carousel, CarouselContent, CarouselItem } from '@/shared/ui/carousel';
import { Title } from '@/shared/ui/title';

type Props = {
    data: Anime[];
};

defineProps<Props>();
</script>

<template>
    <Title text="Recently added anime" />

    <Carousel class="bg-muted px-4 pt-2 shadow">
        <CarouselContent>
            <CarouselItem
                v-for="anime in data"
                :key="anime.id"
                class="basis-1/2 sm:basis-1/3 md:basis-1/4 xl:basis-1/6"
            >
                <div>
                    <Link :href="route('anime.show', anime)">
                        <img
                            class="w-full h-52 sm:h-72 rounded-lg object-fit"
                            loading="lazy"
                            :src="anime.image.path"
                            :alt="anime.title"
                        />
                    </Link>

                    <div class="px-4 py-2">
                        <Link :href="route('anime.show', anime.id)">
                            <h4 class="font-bold text-center text-lg mb-1 truncate">
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
