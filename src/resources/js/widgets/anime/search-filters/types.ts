import type { CountFilter, RangeFilter } from '@/entities/search';

type Filters = {
    years: RangeFilter;
    types: CountFilter;
    statuses: CountFilter;
    genres: CountFilter;
    voiceActing: CountFilter;
};

type SelectedFilters = {
    years: {
        min: number;
        max: number;
    };
    types: string[];
    statuses: string[];
    genres: string[];
    voiceActing: string[];
};

export type { Filters, SelectedFilters };
