<?php

namespace Tests\Unit;

use App\Exceptions\Parsers\UndefinedAnimeParserException;
use App\Factories\ParserFactory;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

/**
 * Class ParserFactoryTest
 * @package Tests\Unit
 */
class ParserFactoryTest extends TestCase
{
    use WithFaker;

    private ParserFactory $parserFactory;

    /**
     * @return void
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->parserFactory = $this->app->make(ParserFactory::class);
    }

    /**
     * @return void
     * @throws UndefinedAnimeParserException
     */
    public function testFactoryCannotParseUnknownSites(): void
    {
        $randomUrl = $this->faker->url;

        $this->expectException(UndefinedAnimeParserException::class);

        $this->parserFactory->getParser($randomUrl);
    }
}
