<?php

declare(strict_types=1);

namespace App\Enums\Telegram\State\Redis;

use App\Enums\Concerns\ProvideValues;

enum UserStateKeyEnum: string
{
    use ProvideValues;

    case WAS_LAST_ACTIVE_AT_KEY          = 'activity:was-last-active-at';
    case LAST_EXECUTED_COMMANDS_LIST_KEY = 'activity:executed-commands-list';
    case SEARCH_RESULT_KEY               = 'activity:search-result';
    case PREVIEW_SEARCH_RESULT_KEY       = 'activity:preview-search-result';
}
