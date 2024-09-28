declare namespace Laravel {
    export interface Pagination<T> {
        data: T[];
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
}

declare namespace Models {
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

    export interface Role {
        name: string;
    }

    export interface Anime extends Id, Timestamps, IsDeleted {
        title: string;
        type: string;
        status: string;
        rating: number;
        episodes: string;
        year: number;
        genres: Genre[];
        image: Image;
        synonyms: AnimeSynonym[];
        urls: AnimeUrl[];
        voice_acting: VoiceActing[];
    }

    export interface Genre extends Id {
        name: string;
    }

    export interface Image extends Id, Timestamps {
        path: string;
    }

    export interface AnimeSynonym extends Id {
        name: string;
    }

    export interface AnimeUrl extends Id {
        url: string;
    }

    export interface VoiceActing extends Id {
        name: string;
    }
}

export type PageProps<T extends Record<string, unknown> = Record<string, unknown>> = T & {
    auth: {
        user: Models.User;
    };
    flash: {
        message?: string;
    };
};
