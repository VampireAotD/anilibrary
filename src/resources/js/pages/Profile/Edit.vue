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
            <UpdateProfileInformationForm
                :must-verify-email="mustVerifyEmail"
                :status="status"
            />

            <UpdatePasswordForm />

            <IntegrationList />

            <DeleteUserForm />

            <Logout />
        </section>
    </AuthenticatedLayout>
</template>
