export interface Role {
    name: string;
}

export interface TelegramUser {
    telegramId: number;
    username?: string;
}

export interface User {
    id: number;
    name: string;
    email: string;
    email_verified_at?: string;
    roles: Role[];
    telegram_user: TelegramUser;
}

export interface Pagination {
    current_page: number;
    first_page_url: string;
    last_page_url: string;
    from: number;
    last_page: number;
    per_page: number;
    next_page_url?: string;
    prev_page_url?: string;
    to: number;
    total: number;
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
    flash: {
        message?: string;
    };
};
