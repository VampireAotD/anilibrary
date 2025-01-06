import { Models } from '@/types';

type Image = Models.Id &
    Models.Timestamps & {
        path: string;
    };

export { type Image };
