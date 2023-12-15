<script setup lang="ts">
import { computed } from 'vue';
import { StatisticCard } from '@/shared/ui/statistic-card';

type Props = {
    animeCount: number;
    usersCount: number;
};

const props = defineProps<Props>();

const cards = computed(() => [
    {
        title: 'Parsed anime',
        icon: 'pi pi-star',
        quantity: props.animeCount,
    },
    {
        title: 'Invited users',
        icon: 'pi pi-comments',
        quantity: props.usersCount,
    },
]);
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 mb-10">
        <StatisticCard v-for="(card, index) in cards" :key="index">
            <template #body>
                <transition
                    appear
                    enter-active-class="transition-opacity duration-500"
                    leave-active-class="transition-opacity duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <!-- eslint-disable-next-line vue/require-toggle-inside-transition -->
                    <span
                        :class="card.icon"
                        class="text-5xl text-black dark:text-white text-center w-16 h-16 fill-current mr-4 lg:block"
                    >
                    </span>
                </transition>

                <transition
                    appear
                    enter-active-class="transition-opacity duration-500"
                    leave-active-class="transition-opacity duration-300"
                    enter-from-class="opacity-0"
                    enter-to-class="opacity-100"
                    leave-from-class="opacity-100"
                    leave-to-class="opacity-0"
                >
                    <!-- eslint-disable-next-line vue/require-toggle-inside-transition -->
                    <div>
                        <p class="font-semibold text-black dark:text-white">
                            {{ card.quantity }}
                        </p>
                        <p class="font-semibold text-black dark:text-white">
                            {{ card.title }}
                        </p>
                    </div>
                </transition>
            </template>
        </StatisticCard>
    </div>
</template>

<style scoped></style>
