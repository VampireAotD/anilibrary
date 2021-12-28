<?php

namespace App\Parsers;

use App\Exceptions\Parsers\InvalidUrlException;
use App\Models\Anime;
use App\Repositories\GenreRepository;
use App\Repositories\VoiceActingRepository;
use App\Services\AnimeService;
use App\Services\GenreService;
use App\Services\VoiceActingService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use voku\helper\HtmlDomParser;
use App\Enums\AnimeHandlerEnum;

/**
 * Class AnimeGoParser
 * @package App\Parsers
 */
class AnimeGoParser extends Parser
{
    public function __construct(
        Client $client,
        private VoiceActingRepository $voiceActingRepository,
        private VoiceActingService $voiceActingService,
        private AnimeService $animeService,
        private GenreRepository $genreRepository,
        private GenreService $genreService
    )
    {
        $this->client = $client;
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
        $dom = $this->getInitialData($url);

        $parsedData = $this->parseInitialData($dom, $url);

        if (in_array(false, array_values($parsedData), true)) {
            throw new InvalidUrlException(AnimeHandlerEnum::INVALID_URL->value);
        }

        $imageUrl = substr($parsedData['image'], 0, stripos($parsedData['image'], ' '));
        $rating = str_replace(',', '.', $parsedData['rating']);

        $parsedData['image'] = $imageUrl;
        $parsedData['rating'] = $rating;
        $parsedData['telegramId'] = $telegramId;

        return $this->animeService->create($parsedData);
    }

    /**
     * @param HtmlDomParser $domParser
     * @return false|string
     */
    protected function getTitle(HtmlDomParser $domParser): false|string
    {
        if (!$title = $domParser->findOneOrFalse('.anime-title div h1')) {
            return false;
        }

        return $title->text;
    }

    /**
     * @param HtmlDomParser $domParser
     * @return false|array
     */
    protected function syncVoiceActing(HtmlDomParser $domParser): false|array
    {
        $voiceActingWrapper = $domParser
            ->findOneOrFalse('.anime-info .row dt:contains(Озвучка)');

        if (!$voiceActingWrapper) {
            return false;
        }

        $voiceActingList = $voiceActingWrapper->nextSibling();

        if (!$voiceActingList) {
            return false;
        }

        $voiceActing = explode(',', $voiceActingList->text());

        $voiceActing = array_map('trim', $voiceActing);

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
     * @return false|string
     */
    protected function getImage(HtmlDomParser $domParser): false|string
    {
        if(!$image = $domParser->findOneOrFalse('.anime-poster img')){
            return false;
        }

        return $image->getAttribute('srcset');
    }

    /**
     * @param HtmlDomParser $domParser
     * @return false|string
     */
    protected function getStatus(HtmlDomParser $domParser): false|string
    {
        $statusWrapper = $domParser
            ->findOneOrFalse('.anime-info .row dt:contains(Статус)');

        if (!$statusWrapper) {
            return false;
        }

        $voiceActingText = $statusWrapper->nextSibling();

        if (!$voiceActingText) {
            return false;
        }

        return $voiceActingText->findOne('a')->text;
    }

    /**
     * @param HtmlDomParser $domParser
     * @return false|string
     */
    protected function getRating(HtmlDomParser $domParser): false|string
    {
        if(!$rating = $domParser->findOneOrFalse('.rating-value')){
            return false;
        }

        return $rating->text;
    }

    /**
     * @param HtmlDomParser $domParser
     * @return false|string
     */
    protected function getEpisodes(HtmlDomParser $domParser): false|string
    {
        $episodesWrapper = $domParser
            ->findOneOrFalse('.anime-info .row dt:contains(Эпизоды)');

        if (!$episodesWrapper) {
            return false;
        }

        $episodesText = $episodesWrapper->nextSibling();

        if (!$episodesText) {
            return false;
        }

        return $episodesText->text;
    }

    /**
     * @param HtmlDomParser $domParser
     * @return false|array
     */
    protected function syncGenres(HtmlDomParser $domParser): false|array
    {
        $genresWrapper = $domParser
            ->findOneOrFalse('.anime-info .row dt:contains(Жанр)');

        if (!$genresWrapper) {
            return false;
        }

        $genresList = $genresWrapper->nextSibling();

        if (!$genresList) {
            return false;
        }

        $genres = explode(',', $genresList->text());

        $genres = array_map('trim', $genres);

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
}
