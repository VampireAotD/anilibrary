<?php

declare(strict_types=1);

namespace App\Enums\Telegram\State;

use App\Enums\Concerns\ProvideValues;

enum UserStateKeyEnum: string
{
    use ProvideValues;

    case SEARCH_RESULT_KEY         = 'activity:search-result';
    case PREVIEW_SEARCH_RESULT_KEY = 'activity:preview-search-result';
}
