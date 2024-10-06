<script setup lang="ts">
import { type CountFilter } from '@/entities/search';
import { Checkbox } from '@/shared/ui/checkbox';

type Props = {
    name: string;
    data: CountFilter;
};

defineProps<Props>();

const model = defineModel<string[]>();
const emit = defineEmits<{ change: CountFilter }>();
</script>

<template>
    <fieldset>
        <legend>{{ name }}</legend>

        <div class="flex flex-col max-h-40 overflow-y-auto space-y-2 p-2">
            <label
                v-for="(value, key) in data"
                :key="value"
                class="inline-flex items-center"
            >
                <Checkbox v-model="model" :value="key" @change="emit('change')" />

                <span class="ml-2">{{ key }} ({{ value }})</span>
            </label>
        </div>
    </fieldset>
</template>

<style scoped></style>
