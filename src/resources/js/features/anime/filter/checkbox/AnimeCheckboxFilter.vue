<script setup lang="ts">
import { Checkbox } from '@/shared/ui/checkbox';

type Props = {
    name: string;
    data: Record<string, number>;
};

defineProps<Props>();

const model = defineModel();
const emit = defineEmits<{ change: string[] }>();
</script>

<template>
    <h3 class="text-md font-semibold mb-2">{{ name }}</h3>

    <div class="flex flex-col max-h-40 overflow-y-auto space-y-2">
        <label
            v-for="(counter, value) in data"
            :key="value"
            class="inline-flex items-center p-x-2"
        >
            <Checkbox v-model="model" :value="value" @change="emit('change', model)" />

            <span class="ml-2">{{ value }} ({{ counter }})</span>
        </label>
    </div>
</template>

<style scoped></style>
