<?php

declare(strict_types=1);

namespace App\Exceptions\UseCase\Telegram;

use Exception;

final class AnimeMessageException extends Exception
{
    public static function animeNotFound(string $id): AnimeMessageException
    {
        return new AnimeMessageException("Anime with id $id not found");
    }

    public static function couldNotGetDataForPage(int $page): AnimeMessageException
    {
        return new AnimeMessageException("Could not get page $page");
    }

    public static function noSearchResultsAvailable(): AnimeMessageException
    {
        return new AnimeMessageException('No available search results');
    }
}
