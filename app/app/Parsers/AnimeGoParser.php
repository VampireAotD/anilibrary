<?php

namespace App\Parsers;

use App\Exceptions\Parsers\InvalidUrlException;
use App\Models\Anime;
use App\Repositories\Contracts\VoiceActing\Repository;
use App\Services\AnimeService;
use App\Services\VoiceActingService;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use voku\helper\HtmlDomParser;
use voku\helper\SimpleHtmlDomInterface;
use App\Enums\AnimeHandlerEnum;

/**
 * Class AnimeGoParser
 * @package App\Parsers
 */
class AnimeGoParser extends Parser
{
    public function __construct(
        Client $client,
        private Repository $voiceActingRepository,
        private VoiceActingService $voiceActingService,
        private AnimeService $animeService
    )
    {
        $this->client = $client;
    }

    /**
     * @param string $url
     * @return Anime
     * @throws InvalidUrlException
     * @throws GuzzleException
     */
    public function parse(string $url): Anime
    {
        $dom = $this->getInitialData($url);

        $title = $this->getTitle($dom);
        $voiceActing = $this->syncVoiceActing($dom);
        $image = $this->getImage($dom);

        if (!$title || !$voiceActing || !$image) {
            throw new InvalidUrlException(AnimeHandlerEnum::INVALID_URL->value);
        }

        return $this->animeService->create([
            'title' => $title->innerhtml,
            'url' => $url,
            'image' => $image->getAttribute('src'),
        ], $voiceActing);
    }

    /**
     * @param HtmlDomParser $domParser
     * @return false|SimpleHtmlDomInterface
     */
    protected function getTitle(HtmlDomParser $domParser): false|SimpleHtmlDomInterface
    {
        return $domParser->findOneOrFalse('.anime-title div h1');
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
     * @return false|SimpleHtmlDomInterface
     */
    protected function getImage(HtmlDomParser $domParser): false|SimpleHtmlDomInterface
    {
        return $domParser->findOneOrFalse('.anime-poster img');
    }
}
