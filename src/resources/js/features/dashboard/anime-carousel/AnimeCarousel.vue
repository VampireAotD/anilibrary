<script setup lang="ts">
import { reactive, ref } from 'vue';

import Carousel from 'primevue/carousel';
import DeferredContent from 'primevue/deferredcontent';

import { Link } from '@inertiajs/vue3';

import { Anime } from '@/entities/anime';
import { SectionTitle } from '@/shared/ui/section-title';

type Props = {
    display: number;
    scroll: number;
    data: Anime[];
};

defineProps<Props>();

const customStyles = reactive({
    item: () => ({
        class: ['overflow-hidden'],
    }),
    previousButton: ['text-black dark:text-red-500'],
    nextButton: ['text-black dark:text-red-500'],
});

const responsiveOptions = ref([
    {
        breakpoint: '1400px',
        numVisible: 2,
        numScroll: 1,
    },
    {
        breakpoint: '1280px',
        numVisible: 4,
        numScroll: 1,
    },
    {
        breakpoint: '767px',
        numVisible: 2,
        numScroll: 1,
    },
    {
        breakpoint: '575px',
        numVisible: 1,
        numScroll: 1,
    },
]);
</script>

<template>
    <DeferredContent>
        <SectionTitle title="Recently added anime" />

        <Carousel
            :value="data"
            :show-indicators="false"
            :num-visible="display"
            :num-scroll="scroll"
            :pt="customStyles"
            :responsive-options="responsiveOptions"
        >
            <template #item="slotProps">
                <div
                    class="flex flex-col justify-center h-full overflow-hidden py-3 px-3"
                >
                    <div class="relative rounded h-[80%]">
                        <img
                            class="h-full w-full"
                            :src="slotProps.data.image.path"
                            :alt="slotProps.data.title"
                        />
                    </div>

                    <div class="h-[5%] mt-3">
                        <Link
                            class="block truncate text-black dark:text-white"
                            target="_blank"
                            :href="route('anime.show', slotProps.data.id)"
                            :title="slotProps.data.title"
                        >
                            {{ slotProps.data.title }}
                        </Link>
                    </div>
                </div>
            </template>
        </Carousel>
    </DeferredContent>
</template>

<style scoped></style>
