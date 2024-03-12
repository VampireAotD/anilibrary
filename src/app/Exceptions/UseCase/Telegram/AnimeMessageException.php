<?php

declare(strict_types=1);

namespace App\Exceptions\UseCase\Telegram;

use Exception;

class AnimeMessageException extends Exception
{
    public static function animeNotFound(string $id): static
    {
        return new static("Anime with id $id not found");
    }

    public static function couldNotGetDataForPage(int $page): static
    {
        return new static("Could not get page $page");
    }

    public static function noSearchResultsAvailable(): static
    {
        return new static('No available search results');
    }
}
