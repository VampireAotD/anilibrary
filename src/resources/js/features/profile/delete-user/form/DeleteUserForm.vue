<script setup lang="ts">
import { ref } from 'vue';

import { DeleteUserModal } from '@/features/profile/delete-user';
import { Block } from '@/shared/ui/block';
import { Button } from '@/shared/ui/button';

const confirmingUserDeletion = ref<boolean>(false);

const toggleConfirmationModal = () =>
    (confirmingUserDeletion.value = !confirmingUserDeletion.value);
</script>

<template>
    <Block as="section" class="sm:p-8">
        <div class="max-w-xl space-y-6">
            <header>
                <h2 class="text-lg font-medium">Delete Account</h2>

                <p class="mt-1 text-sm">
                    Once your account is deleted, all of its resources and data will be
                    permanently deleted. Before deleting your account, please download any
                    data or information that you wish to retain.
                </p>
            </header>

            <Button variant="destructive" @click="toggleConfirmationModal">
                Delete Account
            </Button>

            <DeleteUserModal
                :show="confirmingUserDeletion"
                @close="toggleConfirmationModal"
            />
        </div>
    </Block>
</template>
