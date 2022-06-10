<?php

declare(strict_types=1);

namespace App\Parsers\Traits;

use App\Enums\AnimeHandlerEnum;
use App\Exceptions\Parsers\InvalidUrlException;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Trait CanParseData
 * @package App\Parsers\Traits
 */
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

        $parsedData = $this->parseInitialData($dom, $url, $telegramId)->toArray();

        if (in_array('', array_values($parsedData), true)) {
            throw new InvalidUrlException(AnimeHandlerEnum::INVALID_URL->value);
        }

        return $parsedData;
    }
}
