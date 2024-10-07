<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { CircleUser } from 'lucide-vue-next';

import { Button } from '@/shared/ui/button';
import { Dropdown } from '@/shared/ui/dropdown';
</script>

<template>
    <Dropdown align="left" class="text-center">
        <template #trigger>
            <Button type="button" size="rounded" variant="outline">
                <CircleUser />
            </Button>
        </template>

        <template #content>
            <Link
                :href="route('profile.edit')"
                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out"
            >
                Profile
            </Link>

            <Link
                :href="route('logout')"
                class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out"
                method="post"
                as="button"
            >
                Log Out
            </Link>
        </template>
    </Dropdown>
</template>

<style scoped></style>
