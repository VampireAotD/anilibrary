import { Laravel, Models } from '@/types';

type AnimePagination = Laravel.Pagination<Models.Anime>;

type AddedAnimePerDomain = Record<string, number>;

type AddedAnimePerMonth = number[];

export { type AnimePagination, type AddedAnimePerDomain, type AddedAnimePerMonth };
