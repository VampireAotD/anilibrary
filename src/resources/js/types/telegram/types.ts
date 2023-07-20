export enum WidgetSize {
    Large = 'large',
    Medium = 'medium',
    Small = 'small',
}

export enum RequestAccess {
    Write = 'write',
    Read = 'read',
}

export interface TelegramUser {
    id: number
    first_name?: string
    last_name?: string
    username?: string
    photo_url?: string
    auth_date: number
    hash: string
}

export interface TelegramWidgetProps {
    botName?: string
    size?: WidgetSize
    radius?: number
    showUserPic?: boolean
    requestAccess?: RequestAccess
    callbackHandler: (user: TelegramUser) => void
    redirectUrl?: string
}

export declare function onTelegramAuth(user: TelegramUser): void
