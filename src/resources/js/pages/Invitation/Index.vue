<script setup lang="ts">
import { ref } from 'vue';

import { Head, Link } from '@inertiajs/vue3';

import { InvitationPagination, Status } from '@/entities/invitation';
import { Block } from '@/shared/ui/block';
import { Button } from '@/shared/ui/button';
import { Dropdown } from '@/shared/ui/dropdown';
import {
    Table,
    TableBody,
    TableCaption,
    TableCell,
    TableHead,
    TableHeader,
    TableRow,
} from '@/shared/ui/table';
import CreateInvitationModal from '@/widgets/invitation/create-invitation-modal/CreateInvitationModal.vue';
import { AuthenticatedLayout } from '@/widgets/layouts';

type Props = {
    invitations: InvitationPagination;
};

defineProps<Props>();

const showInvitationModal = ref(false);

const toggleInvitationModal = () =>
    (showInvitationModal.value = !showInvitationModal.value);

const canPerformActions = (status: Status) => status === Status.Pending;
</script>

<template>
    <AuthenticatedLayout>
        <Head title="Invitations" />

        <Block>
            <Button @click="toggleInvitationModal">Send invitation</Button>
        </Block>

        <Table class="mt-4">
            <TableCaption>Invitations</TableCaption>

            <TableHeader>
                <TableRow>
                    <TableHead> Email</TableHead>

                    <TableHead> Status</TableHead>
                </TableRow>
            </TableHeader>

            <TableBody>
                <template v-if="invitations.data.length">
                    <TableRow
                        v-for="invitation in invitations.data"
                        :key="invitation.email"
                    >
                        <TableCell>
                            {{ invitation.email }}
                        </TableCell>

                        <TableCell>
                            {{ invitation.status }}
                        </TableCell>

                        <TableCell v-if="canPerformActions(invitation.status)">
                            <Dropdown align="left">
                                <template #trigger>
                                    <span class="cursor-pointer">...</span>
                                </template>

                                <template #content>
                                    <Link
                                        :href="route('invitation.accept', invitation)"
                                        preserve-scroll
                                        method="patch"
                                        as="button"
                                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out"
                                    >
                                        Accept
                                    </Link>

                                    <Link
                                        :href="route('invitation.decline', invitation)"
                                        preserve-scroll
                                        method="delete"
                                        as="button"
                                        class="block w-full px-4 py-2 text-left text-sm leading-5 text-gray-700 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-800 transition duration-150 ease-in-out"
                                    >
                                        Decline
                                    </Link>
                                </template>
                            </Dropdown>
                        </TableCell>
                    </TableRow>
                </template>

                <TableRow v-else>
                    <TableCell class="h-24 text-center">
                        No pending invitations.
                    </TableCell>
                </TableRow>
            </TableBody>
        </Table>

        <CreateInvitationModal
            :visible="showInvitationModal"
            @close="toggleInvitationModal"
        />
    </AuthenticatedLayout>
</template>

<style scoped></style>
