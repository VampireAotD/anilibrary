<?php

declare(strict_types=1);

namespace Tests\Feature\Services\Scraper;

use App\Services\Scraper\ScraperService;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ScraperServiceTest extends TestCase
{
    use WithFaker;

    protected ScraperService $scraperService;

    protected function setUp(): void
    {
        parent::setUp();

        $this->scraperService = $this->app->make(ScraperService::class);
    }

    public function testServiceWillThrowRequestExceptionOnFailure(): void
    {
        Http::fake(function () {
            return Http::response(['error' => 'Invalid URL'], Response::HTTP_NOT_FOUND);
        });

        $this->expectException(RequestException::class);
        $this->scraperService->sendScrapeRequest($this->faker->url);
    }

    public function testServiceWillCreateAcceptableToken(): void
    {
        Http::fake(function (Request $request) {
            $token = str_replace('Bearer ', '', $request->headers()['Authorization'][0]);

            $decodedToken = (array) JWT::decode($token, new Key(config('jwt.secret'), 'HS512'));

            $this->assertArrayHasKey('iss', $decodedToken);
            $this->assertEquals('anilibrary', $decodedToken['iss']);
            $this->assertArrayHasKey('iat', $decodedToken);
            $this->assertArrayHasKey('exp', $decodedToken);

            return Http::response(['data' => 'test data']);
        });

        $response = $this->scraperService->sendScrapeRequest($this->faker->url);

        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertEquals(['data' => 'test data'], $response->json());
    }

    public function testServiceCanSendScrapeRequest(): void
    {
        Http::fake(function (Request $request) {
            $this->assertEquals(config('services.scraper.url') . '/api/v1/anime/scrape', $request->url());
            $this->assertNotEmpty($request->headers()['Authorization']);

            return Http::response(['data' => 'test data']);
        });

        $response = $this->scraperService->sendScrapeRequest($this->faker->url);

        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertEquals(['data' => 'test data'], $response->json());
    }
}
