import type { PageProps } from '@inertiajs/core';
import type { Config } from 'ziggy-js';

declare namespace Models {
    export interface CursorPagination<T> {
        data: T[];
        next_cursor?: string;
        next_page_url?: string;
        path: string;
        per_page: number;
        prev_cursor?: string;
        prev_page_url?: string;
    }

    export interface Id {
        id: string;
    }

    export interface Timestamps {
        created_at?: string;
        updated_at?: string;
    }

    export interface IsDeleted {
        deleted_at?: string;
    }

    export interface Role {
        name: string;
    }

    export interface User extends Id, Timestamps {
        name: string;
        email: string;
        email_verified_at?: string;
        telegram_user?: TelegramUser;
        roles: Role[];
    }

    export interface TelegramUser extends Id, Timestamps {
        user_id?: string;
        telegram_id: number;
        first_name?: string;
        last_name?: string;
        username?: string;
    }

    export interface Breadcrumb {
        title: string;
        url?: string;
        is_current_page?: boolean;
    }
}

export type Breadcrumbs = Models.Breadcrumb[];

export interface Auth {
    user: User;
}

export interface FlashMessage {
    message?: string;
}

export interface SharedData extends PageProps {
    auth: Auth;
    flash: FlashMessage;
    ziggy: Config & { location: string };
    breadcrumbs?: Breadcrumbs;
}
