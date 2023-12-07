<script setup lang="ts">
import DangerButton from '@/Components/Button/DangerButton.vue';
import { ref } from 'vue';
import DeleteUserModal from '@/Pages/Profile/Partials/DeleteUserModal.vue';

const confirmingUserDeletion = ref<boolean>(false);

const toggleConfirmationModal = () =>
    (confirmingUserDeletion.value = !confirmingUserDeletion.value);
</script>

<template>
    <section class="space-y-6">
        <header>
            <h2 class="text-lg font-medium text-gray-900 dark:text-gray-100">
                Delete Account
            </h2>

            <p class="mt-1 text-sm text-gray-600 dark:text-gray-400">
                Once your account is deleted, all of its resources and data will be
                permanently deleted. Before deleting your account, please download any
                data or information that you wish to retain.
            </p>
        </header>

        <DangerButton @click="toggleConfirmationModal">Delete Account</DangerButton>

        <DeleteUserModal
            :show="confirmingUserDeletion"
            @close="toggleConfirmationModal"
        />
    </section>
</template>
