<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

import { ApplicationLogo } from '@/shared/ui/logo';
</script>

<template>
    <div
        class="min-h-dvh flex flex-col sm:justify-center items-center pt-6 sm:pt-0 bg-background"
    >
        <div>
            <Link href="/">
                <ApplicationLogo class="w-40 h-20 fill-current text-gray-500" />
            </Link>
        </div>

        <main
            class="w-full sm:max-w-md mt-6 px-6 py-4 bg-muted shadow-lg overflow-hidden sm:rounded-lg"
        >
            <slot />
        </main>
    </div>
</template>
