import { type AnimeSynonym } from '@/entities/anime-synonym';
import { type AnimeUrl } from '@/entities/anime-url';
import { type Genre } from '@/entities/genre';
import { type Image } from '@/entities/image';
import { type CountFilter, type RangeFilter } from '@/entities/search';
import { type VoiceActing } from '@/entities/voice-acting';
import { Models } from '@/types';

type Anime = Models.Id &
    Models.Timestamps &
    Models.IsDeleted & {
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
    };

type AddedAnimePerDomain = Record<string, number>;

type AddedAnimePerMonth = number[];

type AnimeFilters = {
    years: RangeFilter;
    types: CountFilter;
    statuses: CountFilter;
    genres: CountFilter;
    voiceActing: CountFilter;
};

export {
    type Anime,
    type AddedAnimePerDomain,
    type AddedAnimePerMonth,
    type AnimeFilters,
};
