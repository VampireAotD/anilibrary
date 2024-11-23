<script setup lang="ts">
import { Link } from '@inertiajs/vue3';

import { Invitation } from '@/entities/invitation';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/shared/ui/dropdown-menu';

type Props = {
    invitation: Invitation;
};

defineProps<Props>();
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger>...</DropdownMenuTrigger>

        <DropdownMenuContent align="start">
            <DropdownMenuItem>
                <Link
                    :href="route('invitation.accept', invitation)"
                    preserve-scroll
                    method="patch"
                    as="button"
                    class="block w-full text-start"
                >
                    Accept
                </Link>
            </DropdownMenuItem>

            <DropdownMenuItem>
                <Link
                    :href="route('invitation.decline', invitation)"
                    preserve-scroll
                    method="delete"
                    as="button"
                    class="block w-full text-start"
                >
                    Decline
                </Link>
            </DropdownMenuItem>
        </DropdownMenuContent>
    </DropdownMenu>
</template>

<style scoped></style>
