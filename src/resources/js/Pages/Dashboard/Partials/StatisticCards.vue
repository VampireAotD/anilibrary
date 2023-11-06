<script setup lang="ts">
import { computed } from 'vue';
import Card from '@/Components/StatisticCard.vue';

type Props = {
    animeCount: number;
    usersCount: number;
};

const props = defineProps<Props>();

const cards = computed(() => [
    {
        title: 'Parsed anime',
        quantity: props.animeCount,
    },
    {
        title: 'Invited users',
        quantity: props.usersCount,
    },
]);

const icons: string[] = ['pi pi-star', 'pi pi-comments'];
</script>

<template>
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-2 mb-10">
        <Card
            v-for="(card, index) in cards"
            :key="index"
            :title="card.title"
            :value="card.quantity"
        >
            <template #icon>
                <span
                    v-if="icons[index]"
                    :class="`${icons[index]} text-5xl text-black dark:text-white text-center w-16 h-16 fill-current mr-4 lg:block`"
                >
                </span>
            </template>
        </Card>
    </div>
</template>

<style scoped></style>
