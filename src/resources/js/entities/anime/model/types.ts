import { Laravel, Models } from '@/types';

type AnimePagination = Laravel.Pagination<Models.Anime>;

type AnimePerDomain = Record<string, number>;

export { type AnimePagination, type AnimePerDomain };
