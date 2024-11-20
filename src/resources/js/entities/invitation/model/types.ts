import { Models } from '@/types';

enum Status {
    Pending = 'pending',
    Accepted = 'accepted',
    Declined = 'declined',
}

type Invitation = Models.Id &
    Models.Timestamps & {
        email: string;
        status: Status;
    };

type InvitationPagination = Models.CursorPagination<Invitation>;

export { Status, type Invitation, type InvitationPagination };
