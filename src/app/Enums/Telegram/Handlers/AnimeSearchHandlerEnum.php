<?php

declare(strict_types=1);

namespace App\Enums\Telegram\Handlers;

enum AnimeSearchHandlerEnum: string
{
    case NO_SEARCH_RESULTS = 'По Вашему запросу ничего не было найдено, попробуйте ещё раз';
}
