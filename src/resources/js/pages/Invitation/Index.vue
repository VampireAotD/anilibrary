<script setup lang="ts">
import { ref } from 'vue';

import { Head } from '@inertiajs/vue3';

import { InvitationPagination } from '@/entities/invitation';
import { TableAction } from '@/features/invitation/table-action';
import { Block } from '@/shared/ui/block';
import { Button } from '@/shared/ui/button';
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

                        <TableCell>
                            <TableAction :invitation="invitation" />
                        </TableCell>
                    </TableRow>
                </template>

                <TableRow v-else>
                    <TableCell :colspan="3" class="h-12 text-center">
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
