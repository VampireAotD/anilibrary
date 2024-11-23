<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import { CreateInvitationForm } from '@/features/invitation/create-invitation-form';
import { AuthenticatedLayout } from '@/widgets/layouts';
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Create invitation to Anilibrary" />

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
            <CreateInvitationForm />
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
