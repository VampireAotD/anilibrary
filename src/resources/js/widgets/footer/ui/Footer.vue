<script setup lang="ts">
import { computed } from 'vue';

import { Link } from '@inertiajs/vue3';

const year = computed(() => {
    const currentYear = new Date().getFullYear();
    return `© ${currentYear}`;
});
</script>

<template>
    <footer class="hidden sm:block bg-muted mt-auto border-t-2 border-border">
        <div
            class="w-full mx-auto max-w-screen-xl p-4 md:flex md:items-center md:justify-between"
        >
            <span class="text-sm text-gray-500 sm:text-center dark:text-gray-400">
                {{ year }}
                <Link :href="route('dashboard')" class="hover:underline">Anilibrary</Link
                >. All Rights Reserved.
            </span>

            <ul
                class="flex flex-wrap items-center mt-3 text-sm font-medium text-gray-500 dark:text-gray-400 sm:mt-0"
            >
                <li>
                    <Link
                        :href="route('anime.index')"
                        class="mr-4 hover:underline md:mr-6"
                        >Anime
                    </Link>
                </li>
            </ul>
        </div>
    </footer>
</template>

<style scoped></style>
