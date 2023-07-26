export type ResultType = 'success' | 'error';

export interface ScrapeResult {
    type: ResultType;
    message: string;
}
