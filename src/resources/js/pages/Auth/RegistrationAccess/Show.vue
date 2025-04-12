<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

import { GuestLayout } from '@/widgets/layouts';
</script>

<template>
    <GuestLayout>
        <div class="flex flex-col items-center space-y-4">
            <p class="text-sm">
                Thank you for taking interest in Anilibrary. We will look into your
                request.
            </p>

            <Link
                :href="route('login')"
                class="rounded-md text-sm underline focus:outline-hidden"
            >
                Return to log in
            </Link>
        </div>
    </GuestLayout>
</template>

<style scoped></style>
