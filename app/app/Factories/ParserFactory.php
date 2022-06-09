<?php

declare(strict_types=1);

namespace App\Factories;

use App\Exceptions\Parsers\UndefinedAnimeParserException;
use App\Parsers\AnimeGoParser;
use App\Parsers\AnimeVostParser;
use App\Parsers\Parser;
use App\Enums\AnimeHandlerEnum;
use App\Parsers\YummyAnimeParser;

/**
 * Class ParserFactory
 * @package App\Factories
 */
class ParserFactory
{
    /**
     * @param string $url
     * @return Parser
     * @throws UndefinedAnimeParserException
     */
    public function getParser(string $url): Parser
    {
        switch ($url) {
            case str_contains($url, 'animego'):
                return app(AnimeGoParser::class);
            case str_contains($url, 'yummyanime'):
                return app(YummyAnimeParser::class);
            case str_contains($url, 'animevost'):
                return app(AnimeVostParser::class);
            default:
                throw new UndefinedAnimeParserException(AnimeHandlerEnum::UNDEFINED_PARSER->value);
        }
    }
}
