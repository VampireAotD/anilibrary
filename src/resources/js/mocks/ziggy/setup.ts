import { config } from '@vue/test-utils';

import { Ziggy } from '@/ziggy';
import { route } from 'ziggy-js';

// mocking Ziggy
// https://laracasts.com/discuss/channels/inertia/vitest-inertiajs-errors-when-running-tests-on-pagecomponents
config.global.mocks.route = (name: string) => route(name, undefined, undefined, Ziggy);
