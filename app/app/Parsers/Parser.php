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
     * @return false|SimpleHtmlDomInterface
     */
    abstract protected function getTitle(HtmlDomParser $domParser): false|SimpleHtmlDomInterface;

    /**
     * @param HtmlDomParser $domParser
     * @return false|array
     */
    abstract protected function syncVoiceActing(HtmlDomParser $domParser): false|array;

    /**
     * @param HtmlDomParser $domParser
     * @return false|SimpleHtmlDomInterface
     */
    abstract protected function getImage(HtmlDomParser $domParser): false|SimpleHtmlDomInterface;

    /**
     * @param string $url
     * @return Anime
     */
    abstract public function parse(string $url): Anime;
}
