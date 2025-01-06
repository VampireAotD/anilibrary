import { type Genre } from '@/entities/genre';
import { type Image } from '@/entities/image';
import { type VoiceActing } from '@/entities/voice-acting';
import { Models } from '@/types';

type AnimeSynonym = Models.Id & {
    name: string;
};

type AnimeUrl = Models.Id & {
    url: string;
};

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

export { type Anime };
