<?php

namespace App\Parsers;

use App\Dto\Parsers\ParseInitialDataDto;
use App\Exceptions\Parsers\InvalidUrlException;
use App\Models\Anime;
use App\Parsers\Traits\CanParseData;
use App\Repositories\GenreRepository;
use App\Repositories\VoiceActingRepository;
use App\Services\AnimeService;
use App\Services\GenreService;
use App\Services\VoiceActingService;
use GuzzleHttp\Client;
use GuzzleHttp\Cookie\CookieJar;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\RequestOptions;
use voku\helper\HtmlDomParser;

/**
 * Class Parser
 * @package App\Parsers
 */
abstract class Parser
{
    use CanParseData;

    protected const MINIMAL_ANIME_RATING = 0.0;

    protected const MINIMAL_ANIME_EPISODES = '0 / ?';

    protected const DEFAULT_HEADERS = [
        'Accept' => 'application/json, text/plain, */*',
        'Accept-Language' => 'en-US,en;q=0.5',
        'X-Application-Type' => 'WebClient',
        'X-Client-Version' => '2.10.4',
        'Origin' => 'https://www.google.com',
        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; rv:78.0) Gecko/20100101 Firefox/78.0',
    ];

    protected const MIN_DELAY_IN_MILLISECONDS = 5000;

    protected const MAX_DELAY_IN_MILLISECONDS = 10000;

    protected const MAX_REDIRECTS = 10;

    protected const READ_TIMEOUT = 10;

    public function __construct(
        protected Client                $client,
        protected VoiceActingRepository $voiceActingRepository,
        protected VoiceActingService    $voiceActingService,
        protected AnimeService          $animeService,
        protected GenreRepository       $genreRepository,
        protected GenreService          $genreService
    ) {
    }

    /**
     * @param string $url
     * @param array $options
     * @return string
     * @throws GuzzleException
     */
    protected function sendRequest(string $url, array $options): string
    {
        return $this->client->get($url, $options)->getBody()->getContents();
    }

    /**
     * @param string $url
     * @return HtmlDomParser
     * @throws GuzzleException
     */
    protected function getInitialData(string $url): HtmlDomParser
    {
        $rawHtml = $this->sendRequest($url, [
            RequestOptions::COOKIES => new CookieJar(),
            RequestOptions::HEADERS => self::DEFAULT_HEADERS,
            RequestOptions::DELAY => rand(self::MIN_DELAY_IN_MILLISECONDS, self::MAX_DELAY_IN_MILLISECONDS),
            RequestOptions::ALLOW_REDIRECTS => [
                'max' => self::MAX_REDIRECTS,
            ],
            RequestOptions::READ_TIMEOUT => self::READ_TIMEOUT,
        ]);

        return HtmlDomParser::str_get_html($rawHtml);
    }

    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    abstract protected function getTitle(HtmlDomParser $domParser): string;

    /**
     * @param array $voiceActing
     * @return array
     */
    protected function syncVoiceActingToDb(array $voiceActing): array
    {
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
     * @return array
     */
    abstract protected function syncVoiceActing(HtmlDomParser $domParser): array;

    /**
     * @param string $url
     * @return string
     */
    protected function getDomainFromUrl(string $url): string
    {
        $urlParts = parse_url($url);

        return sprintf('%s://%s', $urlParts['scheme'], $urlParts['host']);
    }

    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    abstract protected function getImage(HtmlDomParser $domParser): string;

    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    abstract protected function getStatus(HtmlDomParser $domParser): string;

    /**
     * @param HtmlDomParser $domParser
     * @return float
     */
    abstract protected function getRating(HtmlDomParser $domParser): float;

    /**
     * @param HtmlDomParser $domParser
     * @return string
     */
    abstract protected function getEpisodes(HtmlDomParser $domParser): string;

    /**
     * @param array $genres
     * @return array
     */
    protected function syncGenresToDb(array $genres): array
    {
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
     * @param HtmlDomParser $domParser
     * @return array
     */
    abstract protected function syncGenres(HtmlDomParser $domParser): array;

    /**
     * @param HtmlDomParser $domParser
     * @param string $url
     * @param int|null $telegramId
     * @return ParseInitialDataDto
     */
    protected function parseInitialData(
        HtmlDomParser $domParser,
        string $url,
        ?int $telegramId = null
    ): ParseInitialDataDto {
        return new ParseInitialDataDto(
            $url,
            $this->getTitle($domParser),
            $this->syncVoiceActing($domParser),
            $this->getImage($domParser),
            $this->getStatus($domParser),
            $this->getRating($domParser),
            $this->getEpisodes($domParser),
            $this->syncGenres($domParser),
            $telegramId ?? config('telebot.adminId')
        );
    }

    /**
     * @param string $url
     * @param int|null $telegramId
     * @return Anime
     * @throws GuzzleException
     * @throws InvalidUrlException
     */
    abstract public function parse(string $url, ?int $telegramId = null): Anime;
}
