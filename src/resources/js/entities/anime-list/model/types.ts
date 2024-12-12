enum Status {
    PlanToWatch = 'plan_to_watch',
    Watching = 'watching',
    OnHold = 'on_hold',
    Completed = 'completed',
    Dropped = 'dropped',
}

type AnimeListEntry = {
    status: Status;
    rating: number;
    episodes: number;
};

type AnimeList = AnimeListEntry[];

export { Status, type AnimeListEntry, type AnimeList };
