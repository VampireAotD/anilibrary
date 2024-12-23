<script setup lang="ts">
import { Link, useForm } from '@inertiajs/vue3';
import { PlusIcon } from 'lucide-vue-next';

import { AnimeListEntry, Status } from '@/entities/anime-list';
import {
    Select,
    SelectContent,
    SelectItem,
    SelectTrigger,
    SelectValue,
} from '@/shared/ui/select';

type Props = {
    animeId: string;
    entry?: AnimeListEntry;
    statuses: Status[];
};

const { animeId, entry, statuses } = defineProps<Props>();

const form = useForm({
    status: entry?.status,
});

const submit = () => {
    form.put(route('anime-list.update', animeId), {
        only: ['entry'],
        preserveScroll: true,
    });
};
</script>

<template>
    <div v-if="entry" class="flex gap-2">
        <Select
            v-model="form.status"
            @update:modelValue="submit"
            :disabled="form.processing"
        >
            <SelectTrigger>
                <SelectValue placeholder="Select status" />
            </SelectTrigger>
            <SelectContent>
                <SelectItem
                    v-for="(index, status) in statuses"
                    :key="index"
                    :value="status"
                >
                    {{ index }}
                </SelectItem>
            </SelectContent>
        </Select>
    </div>

    <Link
        v-else
        :href="route('anime-list.store', { anime_id: animeId })"
        method="post"
        as="button"
        preserve-scroll
        title="Add to list"
        class="px-4 py-2 bg-background hover:bg-muted rounded-lg inline-flex items-center gap-2"
    >
        <PlusIcon />
        Add to list
    </Link>
</template>

<style scoped></style>
