<script setup lang="ts">
import { Toaster } from '@/shared/ui/toast';
import { BottomNavigation } from '@/widgets/bottom-navigation';
import { Footer } from '@/widgets/footer';
import { Header } from '@/widgets/header';
import { Navigation } from '@/widgets/navigation';
</script>

<template>
    <div class="flex gap-2 w-full min-h-dvh">
        <!-- Page Navigation -->
        <Navigation />

        <div class="flex flex-col w-full sm:gap-4 sm:pt-4 sm:pl-14">
            <!-- Page Heading -->
            <Header />

            <!-- Page Content -->
            <main>
                <div class="py-4 sm:px-6 lg:px-6">
                    <slot />
                </div>
            </main>

            <!-- Page Footer -->
            <Footer />

            <!-- Mobile Bottom Navigation -->
            <BottomNavigation />

            <!-- Toast notifications -->
            <Toaster />
        </div>
    </div>
</template>
