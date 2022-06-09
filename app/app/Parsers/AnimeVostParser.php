<?php

declare(strict_types=1);

namespace App\Parsers;

use App\Enums\AnimeStatusEnum;
use App\Enums\VoiceActingSeederEnum;
use App\Exceptions\Parsers\InvalidUrlException;
use App\Models\Anime;
use GuzzleHttp\Exception\GuzzleException;
use voku\helper\HtmlDomParser;

/**
 * Class AnimeVostParser
 * @package App\Parsers
 */
class AnimeVostParser extends Parser
{
    private const AMOUNT_TO_MAKE_FLOAT = 10;

    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    protected function getTitle(HtmlDomParser $domParser): string
    {
        if (!$title = $domParser->findOneOrFalse('.shortstoryHead h1')) {
            return '';
        }

        $title = $title->text;

        return substr($title, 0, strripos($title, ' /'));
    }

    /**
     * @param HtmlDomParser $domParser
     * @return array
     */
    protected function syncVoiceActing(HtmlDomParser $domParser): array
    {
        $animeVostVoiceActing = $this->voiceActingRepository->findByName(VoiceActingSeederEnum::ANIMEVOST->value);

        return [$animeVostVoiceActing?->id];
    }

    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    protected function getImage(HtmlDomParser $domParser): string
    {
        if (!$image = $domParser->findOneOrFalse('.imgRadius')) {
            return '';
        }

        return $image->getAttribute('src');
    }

    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    protected function getStatus(HtmlDomParser $domParser): string
    {
        if (!$domParser->findOneOrFalse('#nexttime')) {
            return AnimeStatusEnum::READY->value;
        }

        return AnimeStatusEnum::ONGOING->value;
    }

    /**
     * @param HtmlDomParser $domParser
     * @return float
     */
    protected function getRating(HtmlDomParser $domParser): float
    {
        if (!$rating = $domParser->findOneOrFalse('.current-rating')) {
            return self::MINIMAL_ANIME_RATING;
        }

        return (int)$rating->text / self::AMOUNT_TO_MAKE_FLOAT;
    }

    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    protected function getEpisodes(HtmlDomParser $domParser): string
    {
        if (!$episodes = $domParser->findOneOrFalse('p strong:contains(Количество серий)')) {
            return self::MINIMAL_ANIME_EPISODES;
        }

        $episodes = $episodes->nextSibling()->text;

        $episodes = str_replace('+', ' ', $episodes);

        return trim(substr($episodes, 0, strripos($episodes, ' (')));
    }

    /**
     * @param HtmlDomParser $domParser
     * @return array
     */
    protected function syncGenres(HtmlDomParser $domParser): array
    {
        if (!$genres = $domParser->findOneOrFalse('p strong:contains(Жанр)')) {
            return [];
        }

        $genres = trim($genres->nextSibling()->text);

        $convertedGenres = mb_convert_case($genres, MB_CASE_TITLE, 'UTF-8');

        $genres = explode(',', $convertedGenres);

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

        $domain = $this->getDomainFromUrl($url);

        $parsedData['image'] = sprintf('%s%s', $domain, $parsedData['image']);

        return $this->animeService->create($parsedData);
    }
}
