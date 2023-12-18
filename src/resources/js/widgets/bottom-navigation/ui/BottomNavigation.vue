<script setup lang="ts">
import { NavigationLink } from '@/features/bottom-navigation/navigation-link';
import { useHasRole } from '@/shared/plugins/user/authorize';
import { computed } from 'vue';

const isOwner = useHasRole('owner');
const gridColumns = computed((): string => (isOwner ? 'grid-cols-4' : 'grid-cols-3'));
</script>

<template>
    <nav
        class="fixed sm:hidden bottom-0 left-0 z-50 w-full h-16 bg-white border-t border-gray-300 dark:bg-gray-800 dark:border-gray-600"
    >
        <div class="grid h-full max-w-lg mx-auto font-medium" :class="gridColumns">
            <NavigationLink text="Home" :url="route('dashboard')">
                <svg
                    class="w-5 h-5 mb-2 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-red-500"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="m19.707 9.293-2-2-7-7a1 1 0 0 0-1.414 0l-7 7-2 2a1 1 0 0 0 1.414 1.414L2 10.414V18a2 2 0 0 0 2 2h3a1 1 0 0 0 1-1v-4a1 1 0 0 1 1-1h2a1 1 0 0 1 1 1v4a1 1 0 0 0 1 1h3a2 2 0 0 0 2-2v-7.586l.293.293a1 1 0 0 0 1.414-1.414Z"
                    />
                </svg>
            </NavigationLink>

            <NavigationLink text="Anime" :url="route('anime.index')">
                <svg
                    class="w-5 h-5 mb-2 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-red-500"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M10 0C4.612 0 0 5.336 0 7c0 1.742 3.546 7 10 7 6.454 0 10-5.258 10-7 0-1.664-4.612-7-10-7Zm0 10a3 3 0 1 1 0-6 3 3 0 0 1 0 6Z"
                    />
                </svg>
            </NavigationLink>

            <NavigationLink
                v-if="hasRole('owner')"
                text="Invitation"
                :url="route('invitation.create')"
            >
                <svg
                    class="w-5 h-5 mb-2 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-red-500"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M18 0H2a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2h3.546l3.2 3.659a1 1 0 0 0 1.506 0L13.454 14H18a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2Zm-8 10H5a1 1 0 0 1 0-2h5a1 1 0 1 1 0 2Zm5-4H5a1 1 0 0 1 0-2h10a1 1 0 1 1 0 2Z"
                    />
                </svg>
            </NavigationLink>

            <NavigationLink text="Profile" :url="route('profile.edit')">
                <svg
                    class="w-5 h-5 mb-2 text-gray-500 dark:text-gray-400 group-hover:text-indigo-600 dark:group-hover:text-red-500"
                    aria-hidden="true"
                    xmlns="http://www.w3.org/2000/svg"
                    fill="currentColor"
                    viewBox="0 0 20 20"
                >
                    <path
                        d="M10 0a10 10 0 1 0 10 10A10.011 10.011 0 0 0 10 0Zm0 5a3 3 0 1 1 0 6 3 3 0 0 1 0-6Zm0 13a8.949 8.949 0 0 1-4.951-1.488A3.987 3.987 0 0 1 9 13h2a3.987 3.987 0 0 1 3.951 3.512A8.949 8.949 0 0 1 10 18Z"
                    />
                </svg>
            </NavigationLink>
        </div>
    </nav>
</template>

<style scoped></style>
