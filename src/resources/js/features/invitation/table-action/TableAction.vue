<script setup lang="ts">
import { computed } from 'vue';

import { Link } from '@inertiajs/vue3';

import { type Invitation, Status } from '@/entities/invitation';
import {
    DropdownMenu,
    DropdownMenuContent,
    DropdownMenuItem,
    DropdownMenuTrigger,
} from '@/shared/ui/dropdown-menu';

type Props = {
    invitation: Invitation;
};

const { invitation } = defineProps<Props>();

const isPending = computed(() => invitation.status === Status.Pending);
const isAccepted = computed(() => invitation.status === Status.Accepted);
</script>

<template>
    <DropdownMenu>
        <DropdownMenuTrigger>...</DropdownMenuTrigger>

        <DropdownMenuContent align="start">
            <DropdownMenuItem v-if="isPending">
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

            <DropdownMenuItem v-if="isAccepted">
                <Link
                    :href="route('invitation.resend', invitation)"
                    preserve-scroll
                    method="post"
                    as="button"
                    class="block w-full text-start"
                >
                    Resend
                </Link>
            </DropdownMenuItem>

            <DropdownMenuItem v-if="isPending">
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
