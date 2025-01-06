<script setup lang="ts">
import { Link } from '@inertiajs/vue3';
import { CircleUser } from 'lucide-vue-next';

import { Button } from '@/shared/ui/button';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/shared/ui/dropdown-menu';
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger as-child>
            <Button class="size-8" type="button" size="rounded" variant="outline">
                <CircleUser />
            </Button>
        </DropdownMenuTrigger>

        <DropdownMenuContent align="end">
            <DropdownMenuItem>
                <Link class="block w-full cursor-pointer" :href="route('profile.edit')">
                    Profile
                </Link>
            </DropdownMenuItem>

            <DropdownMenuItem>
                <Link
                    class="w-full text-start"
                    :href="route('logout')"
                    method="post"
                    as="button"
                >
                    Log Out
                </Link>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>

<style scoped></style>
