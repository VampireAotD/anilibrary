<script setup lang="ts">
import { ref } from 'vue';

import { TextInput } from '@/shared/ui/input';
import { Modal } from '@/shared/ui/modal';

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
                class="w-full ring-1"
                name="search"
                autofocus
                placeholder="Search"
            />
        </template>
    </Modal>
</template>

<style scoped></style>
