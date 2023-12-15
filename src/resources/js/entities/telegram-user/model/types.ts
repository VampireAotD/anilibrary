interface TelegramUser {
    id: number;
    first_name?: string;
    last_name?: string;
    username?: string;
    photo_url?: string;
    auth_date: number;
    hash: string;
}

declare function onTelegramAuth(user: TelegramUser): void;

export { TelegramUser, onTelegramAuth };
