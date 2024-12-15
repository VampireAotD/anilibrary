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

type AnimeListEntryStatistic = {
    status: Status;
    user_count: number;
    percentage: number;
};

type AnimeListStatistics = AnimeListEntryStatistic[];

export {
    Status,
    type AnimeListEntry,
    type AnimeList,
    type AnimeListEntryStatistic,
    type AnimeListStatistics,
};
