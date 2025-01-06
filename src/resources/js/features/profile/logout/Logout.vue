<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

import { Block } from '@/shared/ui/block';
</script>

<template>
    <Block as="section" class="sm:p-8">
        <Link
            class="inline-flex items-center border font-semibold px-4 py-2 text-xs uppercase tracking-widest bg-white dark:bg-gray-800 border-gray-300 dark:border-gray-500 text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 focus:ring-indigo-500 dark:focus:ring-offset-gray-800 disabled:opacity-25 focus:outline-none focus:ring-2 focus:ring-offset-2 transition ease-in-out duration-150"
            :href="route('logout')"
            method="post"
            as="button"
        >
            Logout
        </Link>
    </Block>
</template>

<style scoped></style>
