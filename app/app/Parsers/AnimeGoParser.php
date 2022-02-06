<?php

namespace App\Parsers;

use App\Enums\AnimeStatusEnum;
use App\Exceptions\Parsers\InvalidUrlException;
use App\Models\Anime;
use GuzzleHttp\Exception\GuzzleException;
use voku\helper\HtmlDomParser;

/**
 * Class AnimeGoParser
 * @package App\Parsers
 */
class AnimeGoParser extends Parser
{
    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    protected function getTitle(HtmlDomParser $domParser): string
    {
        if (!$title = $domParser->findOneOrFalse('.anime-title div h1')) {
            return '';
        }

        return $title->text;
    }

    /**
     * @param HtmlDomParser $domParser
     * @return array
     */
    protected function syncVoiceActing(HtmlDomParser $domParser): array
    {
        if (!$voiceActingList = $domParser->findOneOrFalse('.anime-info .row dt:contains(Озвучка) + dd')) {
            return [];
        }

        $voiceActing = explode(
            ',',
            preg_replace("#,\s+#mi", ',', $voiceActingList->text)
        );

        return $this->syncVoiceActingToDb($voiceActing);
    }

    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    protected function getImage(HtmlDomParser $domParser): string
    {
        if (!$image = $domParser->findOneOrFalse('.anime-poster img')) {
            return '';
        }

        $imageUrl = $image->getAttribute('src');

        return str_replace('/media/cache/thumbs_250x350', '', $imageUrl);
    }

    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    protected function getStatus(HtmlDomParser $domParser): string
    {
        if (!$statusText = $domParser->findOneOrFalse('.anime-info .row dt:contains(Статус) + dd')) {
            return AnimeStatusEnum::READY->value;
        }

        $status = AnimeStatusEnum::tryFrom($statusText->text);

        if (!$status) {
            return AnimeStatusEnum::READY->value;
        }

        return $status->value;
    }

    /**
     * @param HtmlDomParser $domParser
     * @return float
     */
    protected function getRating(HtmlDomParser $domParser): float
    {
        if (!$rating = $domParser->findOneOrFalse('.rating-value')) {
            return self::MINIMAL_ANIME_RATING;
        }

        return str_replace(',', '.', $rating->text);
    }

    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    protected function getEpisodes(HtmlDomParser $domParser): string
    {
        if (!$episodesText = $domParser->findOneOrFalse('.anime-info .row dt:contains(Эпизоды) + dd')) {
            return self::MINIMAL_ANIME_EPISODES;
        }

        return $episodesText->text;
    }

    /**
     * @param HtmlDomParser $domParser
     * @return array
     */
    protected function syncGenres(HtmlDomParser $domParser): array
    {
        if (!$genresList = $domParser->findOneOrFalse('.anime-info .row dt:contains(Жанр) + dd')) {
            return [];
        }

        $genres = explode(',', preg_replace("#\s#mi", '', $genresList->text()));

        return $this->syncGenresToDb($genres);
    }


    /**
     * @param string $url
     * @param int|null $telegramId
     * @return Anime
     * @throws GuzzleException
     * @throws InvalidUrlException
     */
    public function parse(string $url, ?int $telegramId = null): Anime
    {
        $parsedData = $this->getParsedData($url, $telegramId);

        return $this->animeService->create($parsedData);
    }
}
