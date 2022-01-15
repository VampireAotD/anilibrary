<?php

namespace App\Parsers\Traits;

use App\Enums\AnimeHandlerEnum;
use App\Exceptions\Parsers\InvalidUrlException;
use GuzzleHttp\Exception\GuzzleException;

trait CanParseData
{
    /**
     * @param string $url
     * @param int|null $telegramId
     * @return array
     * @throws InvalidUrlException
     * @throws GuzzleException
     */
    public function getParsedData(string $url, ?int $telegramId = null): array
    {
        $dom = $this->getInitialData($url);

        $parsedData = $this->parseInitialData($dom, $url, $telegramId);

        if (in_array('', array_values($parsedData), true)) {
            throw new InvalidUrlException(AnimeHandlerEnum::INVALID_URL->value);
        }

        return $parsedData;
    }
}
