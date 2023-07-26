import { AnimeWithRelations } from '@/types/anime/models';

interface LaravelPagination {
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

export interface IndexProps extends LaravelPagination {
    data: AnimeWithRelations[];
}
