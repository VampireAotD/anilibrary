<script setup lang="ts">
import { Head } from '@inertiajs/vue3';

import { CreateInvitationForm } from '@/features/invitation/create-invitation';
import { AuthenticatedLayout } from '@/widgets/layouts';
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Create invitation to Anilibrary" />

        <div class="py-12 sm:pt-12">
            <div class="max-w-7xl mx-auto sm:px-8 lg:px-24">
                <div
                    class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg"
                >
                    <CreateInvitationForm />
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>

<style scoped></style>
