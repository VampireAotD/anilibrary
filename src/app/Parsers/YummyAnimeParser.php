<?php

declare(strict_types=1);

namespace App\Parsers;

use App\Enums\Telegram\AnimeStatusEnum;
use App\Exceptions\Parsers\InvalidUrlException;
use App\Models\Anime;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use Illuminate\Support\Str;
use voku\helper\HtmlDomParser;

/**
 * Class YummyAnimeParser
 * @package App\Parsers
 */
class YummyAnimeParser extends Parser
{
    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    protected function getTitle(HtmlDomParser $domParser): string
    {
        if (!$title = $domParser->findOneOrFalse('.anime-page h1')) {
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
        if (!$voiceActing = $domParser->findOneOrFalse('.animeVoices')) {
            return [];
        }

        $voiceActingList = $voiceActing->findMultiOrFalse('li');

        $voiceActingText = [];

        foreach ($voiceActingList as $voiceActing) {
            if ($voiceActing->parent()->getAttribute('class') === 'animeVoices') {
                $voiceActingText[] = substr(
                    $voiceActing->text,
                    0,
                    stripos($voiceActing->text, 'Озвучили') ?: null
                );
            }
        }

        return $this->syncVoiceActingToDb($voiceActingText);
    }

    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    protected function getImage(HtmlDomParser $domParser): string
    {
        if (!$image = $domParser->findOneOrFalse('.poster-block img')) {
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
        if (!$status = $domParser->findOneOrFalse('.badge')) {
            return AnimeStatusEnum::READY->value;
        }

        $convertedStatus = mb_convert_case($status->text, MB_CASE_TITLE, 'UTF-8');

        $status = AnimeStatusEnum::tryFrom(ucfirst($convertedStatus));

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
        if (!$rating = $domParser->findOneOrFalse('.main-rating')) {
            return self::MINIMAL_ANIME_RATING;
        }

        return (float) $rating->text;
    }

    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    protected function getEpisodes(HtmlDomParser $domParser): string
    {
        if (!$episodes = $domParser->findOneOrFalse('li span:contains(Серии)')) {
            return self::MINIMAL_ANIME_EPISODES;
        }

        return $episodes->nextSibling()->text;
    }

    /**
     * @param HtmlDomParser $domParser
     * @return array
     */
    protected function syncGenres(HtmlDomParser $domParser): array
    {
        if (!$genres = $domParser->findOneOrFalse('li span:contains(Жанр)')) {
            return [];
        }

        $genres = $genres->parent()->findOneOrFalse('ul')->text;

        $genres = preg_replace('#\n\s+#mi', PHP_EOL, $genres);

        $genres = explode(PHP_EOL, $genres);

        return $this->syncGenresToDb($genres);
    }

    /**
     * @param string $siteUrl
     * @param string $imageUrl
     * @return string
     * @throws GuzzleException
     */
    private function getTmpImagePath(string $siteUrl, string $imageUrl): string
    {
        $domain = $this->getDomainFromUrl($siteUrl);

        $this->sendRequest(
            sprintf('%s%s', $domain, $imageUrl),
            [
                RequestOptions::HEADERS => self::DEFAULT_HEADERS,
                RequestOptions::SINK    => $storagePath = storage_path(sprintf('tmp/%s.webp', Str::random())),
            ]
        );

        return $storagePath;
    }

    /**
     * @param string   $url
     * @param int|null $telegramId
     * @return Anime
     * @throws GuzzleException
     * @throws InvalidUrlException
     */
    public function parse(string $url, ?int $telegramId = null): Anime
    {
        $parsedData = $this->getParsedData($url, $telegramId);

        $parsedData['image'] = $this->getTmpImagePath($url, $parsedData['image']);

        return $this->animeService->create($parsedData);
    }
}
