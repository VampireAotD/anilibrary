<script setup lang="ts">
import { Home, LogOut, Mail, Shuffle, Table } from 'lucide-vue-next';

import { NavigationLink } from '@/features/navigation/navigation-link';
</script>

<template>
    <aside
        class="fixed inset-y-0 left-0 z-10 w-14 hidden lg:flex flex-col justify-between shadow-lg bg-muted"
    >
        <!-- Navigation links -->
        <nav class="flex flex-col items-center gap-4 px-2 sm:py-5">
            <NavigationLink
                :href="route('dashboard')"
                :active="route().current('dashboard')"
                title="Dashboard"
                async
            >
                <Home />
            </NavigationLink>

            <NavigationLink
                v-if="hasRole('owner')"
                :href="route('invitation.index')"
                :active="route().current('invitation.index')"
                title="Invitations"
                async
            >
                <Mail />
            </NavigationLink>

            <NavigationLink
                :href="route('anime.index')"
                :active="route().current('anime.index')"
                title="Anime"
                async
                prefetch
            >
                <Table />
            </NavigationLink>

            <NavigationLink :href="route('anime.random')" title="Random anime" async>
                <Shuffle />
            </NavigationLink>
        </nav>

        <nav class="flex flex-col items-center gap-4 px-2 sm:py-5">
            <NavigationLink :href="route('logout')" method="post" as="button">
                <LogOut />
            </NavigationLink>
        </nav>
    </aside>
</template>

<style scoped></style>
