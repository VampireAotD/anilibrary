import { AnimeSynonym } from '@/entities/anime-synonym';
import { AnimeUrl } from '@/entities/anime-url';
import { Genre } from '@/entities/genre';
import { Image } from '@/entities/image';
import { VoiceActing } from '@/entities/voice-acting';
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

export { type Anime, type AddedAnimePerDomain, type AddedAnimePerMonth };
