<script setup lang="ts">
import { computed } from 'vue';

import { type CountFilter } from '@/entities/search';
import { Multiselect } from '@/shared/ui/multiselect';

type Props = {
    name: string;
    data: CountFilter;
};

const { name, data } = defineProps<Props>();
const model = defineModel<string[]>();

const items = computed(() =>
    Object.entries(data ?? []).map(([key, value]) => ({
        label: `${key} (${value})`,
        value: key,
    }))
);
</script>

<template>
    <fieldset>
        <legend>{{ name }}</legend>

        <div class="flex flex-col max-h-40 overflow-y-auto space-y-2 p-2">
            <Multiselect v-model="model" :items="items" />
        </div>
    </fieldset>
</template>

<style scoped></style>
