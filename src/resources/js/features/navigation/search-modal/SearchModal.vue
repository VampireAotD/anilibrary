<script setup lang="ts">
import { Modal } from '@/shared/ui/modal';
import { TextInput } from '@/shared/ui/input/text';
import { ref } from 'vue';

type Props = {
    visible: boolean;
};

defineProps<Props>();
defineEmits<{ closed: [] }>();

const query = ref<string>('');
</script>

<template>
    <Modal
        :visible="visible"
        :close-button="false"
        close-on-escape
        close-on-outside-click
        @close="$emit('closed')"
    >
        <template #header>
            <TextInput
                ref="searchInput"
                v-model="query"
                name="search"
                autofocus
                class="w-full text-xs ring-1 bg-transparent ring-gray-200 dark:ring-zinc-600 focus:ring-red-300 px-5 text-gray-600 dark:text-white py-3 rounded-full w-full outline-none focus:ring-1"
                placeholder="Search"
            />
        </template>
    </Modal>
</template>

<style scoped></style>
