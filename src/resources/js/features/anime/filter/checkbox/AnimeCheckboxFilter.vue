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
    <fieldset>
        <legend>{{ name }}</legend>

        <div class="flex flex-col max-h-40 overflow-y-auto space-y-2 p-2">
            <label
                v-for="(counter, value) in data"
                :key="value"
                class="inline-flex items-center"
            >
                <Checkbox
                    v-model="model"
                    :value="value"
                    @change="emit('change', model)"
                />

                <span class="ml-2">{{ value }} ({{ counter }})</span>
            </label>
        </div>
    </fieldset>
</template>

<style scoped></style>
