<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import { DeleteUserForm } from '@/features/profile/delete-user';
import { IntegrationList } from '@/features/profile/integration-list';
import { Logout } from '@/features/profile/logout';
import {
    UpdatePasswordForm,
    UpdateProfileInformationForm,
} from '@/features/profile/update-profile';
import { AuthenticatedLayout } from '@/widgets/layouts';

defineProps<{
    mustVerifyEmail?: boolean;
    status?: string;
}>();
</script>

<template>
    <Head title="Profile" />

    <AuthenticatedLayout>
        <section class="flex flex-col gap-4">
            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <UpdateProfileInformationForm
                    :must-verify-email="mustVerifyEmail"
                    :status="status"
                    class="max-w-xl"
                />
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <UpdatePasswordForm class="max-w-xl" />
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <IntegrationList />
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <DeleteUserForm class="max-w-xl" />
            </div>

            <div class="p-4 sm:p-8 bg-white dark:bg-gray-800 shadow sm:rounded-lg">
                <Logout />
            </div>
        </section>
    </AuthenticatedLayout>
</template>
