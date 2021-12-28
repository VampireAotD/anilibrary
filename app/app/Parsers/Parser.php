<?php

namespace App\Parsers;

use App\Models\Anime;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use voku\helper\HtmlDomParser;
use voku\helper\SimpleHtmlDomInterface;

/**
 * Class Parser
 * @package App\Parsers
 */
abstract class Parser
{
    public function __construct(protected Client $client)
    {
    }

    /**
     * @param string $url
     * @return HtmlDomParser
     * @throws GuzzleException
     */
    protected function getInitialData(string $url): HtmlDomParser
    {
        $rawHtml = $this->client->get($url)->getBody()->getContents();

        return HtmlDomParser::str_get_html($rawHtml);
    }

    /**
     * @param HtmlDomParser $domParser
     * @return false|string
     */
    abstract protected function getTitle(HtmlDomParser $domParser): false|string;

    /**
     * @param HtmlDomParser $domParser
     * @return false|array
     */
    abstract protected function syncVoiceActing(HtmlDomParser $domParser): false|array;

    /**
     * @param HtmlDomParser $domParser
     * @return false|string
     */
    abstract protected function getImage(HtmlDomParser $domParser): false|string;

    /**
     * @param HtmlDomParser $domParser
     * @return false|string
     */
    abstract protected function getStatus(HtmlDomParser $domParser): false|string;

    /**
     * @param HtmlDomParser $domParser
     * @return false|string
     */
    abstract protected function getRating(HtmlDomParser $domParser): false|string;

    /**
     * @param HtmlDomParser $domParser
     * @return false|string
     */
    abstract protected function getEpisodes(HtmlDomParser $domParser): false|string;

    /**
     * @param HtmlDomParser $domParser
     * @return false|array
     */
    abstract protected function syncGenres(HtmlDomParser $domParser): false|array;

    /**
     * @param HtmlDomParser $domParser
     * @param string $url
     * @return array
     */
    protected function parseInitialData(HtmlDomParser $domParser, string $url): array
    {
        return [
            'url' => $url,
            'title' => $this->getTitle($domParser),
            'voiceActing' => $this->syncVoiceActing($domParser),
            'image' => $this->getImage($domParser),
            'status' => $this->getStatus($domParser),
            'rating' => $this->getRating($domParser),
            'episodes' => $this->getEpisodes($domParser),
            'genres' => $this->syncGenres($domParser),
        ];
    }

    /**
     * @param string $url
     * @param int|null $telegramId
     * @return Anime
     */
    abstract public function parse(string $url, ?int $telegramId = null): Anime;
}
