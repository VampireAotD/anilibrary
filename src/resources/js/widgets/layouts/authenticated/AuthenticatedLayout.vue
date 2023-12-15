<script setup lang="ts">
import { Footer } from '../../footer';
import { Navigation } from '@/features/navigation';
</script>

<template>
    <div>
        <div class="flex flex-col min-h-screen bg-gray-100 dark:bg-gray-900">
            <!-- Navigation -->
            <Navigation />

            <!-- Page Heading -->
            <header v-if="$slots.header" class="bg-white dark:bg-gray-800 shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    <slot name="header" />
                </div>
            </header>

            <!-- Page Content -->
            <main>
                <slot />
            </main>

            <!-- Page Footer -->
            <Footer />
        </div>
    </div>
</template>
