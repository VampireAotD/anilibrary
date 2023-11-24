import { Laravel, Models } from '@/types';

export type AnimePagination = Laravel.Pagination<Models.Anime>;

export type AnimePerDomain = Record<string, number>;
