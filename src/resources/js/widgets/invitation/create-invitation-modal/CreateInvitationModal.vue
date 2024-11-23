<script setup lang="ts">
import { CreateInvitationForm } from '@/features/invitation/create-invitation-form';
import { Modal } from '@/shared/ui/modal';

type Props = {
    visible: boolean;
};

defineProps<Props>();
defineEmits<{ close: [] }>();
</script>

<template>
    <Modal
        :visible="visible"
        close-on-outside-click
        close-on-escape
        @close="$emit('close')"
    >
        <template #header>
            <h2 class="text-lg font-semibold text-foreground">Send invitation</h2>
        </template>

        <template #body>
            <CreateInvitationForm @sent="$emit('close')" />
        </template>
    </Modal>
</template>

<style scoped></style>
