export interface Anime {
    id: string
    title: string
    status: string
    episodes: string
    rating: number
}

export interface IndexProps {
    data: Anime[]
    next_page_url?: string
    prev_page_url?: string
}
