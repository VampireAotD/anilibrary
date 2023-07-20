export interface Role {
    name: string
}

export interface TelegramUser {
    telegramId: number
    username?: string
}

export interface User {
    id: number
    name: string
    email: string
    email_verified_at?: string
    roles: Role[]
    telegram_user: TelegramUser
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: User;
    };
};
