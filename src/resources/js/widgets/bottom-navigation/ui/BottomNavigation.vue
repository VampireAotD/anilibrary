<script setup lang="ts">
import { HomeIcon, MailIcon, ShuffleIcon, TableIcon } from 'lucide-vue-next';

import { NavigationLink } from '@/features/bottom-navigation/navigation-link';
import { useHasRole } from '@/shared/plugins/user/authorize';

const isOwner = useHasRole('owner');
</script>

<template>
    <nav class="fixed lg:hidden bottom-0 left-0 z-50 h-14 w-dvw bg-muted border-t-2">
        <div
            class="grid divide-x h-full"
            :class="isOwner ? 'grid-cols-4' : 'grid-cols-3'"
        >
            <NavigationLink :href="route('dashboard')">
                <HomeIcon />
                <span class="sr-only">Home</span>
            </NavigationLink>

            <NavigationLink :href="route('anime.index')">
                <TableIcon />
                <span class="sr-only">Anime</span>
            </NavigationLink>

            <NavigationLink v-if="isOwner" :href="route('invitation.index')">
                <MailIcon />
                <span class="sr-only">Invitations</span>
            </NavigationLink>

            <NavigationLink :href="route('anime.random')">
                <ShuffleIcon />
                <span class="sr-only">Random anime</span>
            </NavigationLink>
        </div>
    </nav>
</template>

<style scoped></style>
