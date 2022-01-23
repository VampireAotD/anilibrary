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
        $voiceActingList = $domParser
            ->findOneOrFalse('.anime-info .row dt:contains(Озвучка) + dd');

        if (!$voiceActingList) {
            return [];
        }

        $voiceActing = explode(
            ',',
            preg_replace("#,\s+#mi", ',', $voiceActingList->text)
        );

        $voiceActingInDb = $this->voiceActingRepository->findSimilarByNames($voiceActing, ['name'])
            ->pluck('name')
            ->toArray();

        $notInDb = array_diff($voiceActing, $voiceActingInDb);

        if ($notInDb) {
            $this->voiceActingService->batchInsert($notInDb);
        }

        return $this->voiceActingRepository->findSimilarByNames($voiceActing, ['id'])
            ->pluck('id')
            ->toArray();
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
        $statusText = $domParser
            ->findOneOrFalse('.anime-info .row dt:contains(Статус) + dd');

        if (!$statusText) {
            return '';
        }

        $status = AnimeStatusEnum::tryFrom($statusText->text);

        if (!$status) {
            return '';
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
        $episodesText = $domParser
            ->findOneOrFalse('.anime-info .row dt:contains(Эпизоды) + dd');

        if (!$episodesText) {
            return '';
        }

        return $episodesText->text;
    }

    /**
     * @param HtmlDomParser $domParser
     * @return array
     */
    protected function syncGenres(HtmlDomParser $domParser): array
    {
        $genresList = $domParser
            ->findOneOrFalse('.anime-info .row dt:contains(Жанр) + dd');

        if (!$genresList) {
            return [];
        }

        $genres = explode(',', preg_replace("#\s#mi", '', $genresList->text()));

        $genresInDb = $this->genreRepository->findSimilarByNames($genres, ['name'])
            ->pluck('name')
            ->toArray();

        $notInDb = array_diff($genres, $genresInDb);

        if ($notInDb) {
            $this->genreService->batchInsert($notInDb);
        }

        return $this->genreRepository->findSimilarByNames($genres, ['id'])
            ->pluck('id')
            ->toArray();
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
