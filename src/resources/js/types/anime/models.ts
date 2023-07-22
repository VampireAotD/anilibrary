export interface Image {
    path: string
}

export interface URL {
    url: string
}

export interface Synonym {
    synonym: string
}

export interface Genre {
    name: string
}

export interface VoiceActing {
    name: string
}

export interface Anime {
    id: string
    title: string
    status: string
    episodes: string
    rating: number
}

export interface AnimeWithRelations extends Anime {
    image: Image
    urls: URL[]
    synonyms: Synonym[],
    voice_acting: VoiceActing[],
    genres: Genre[],
}