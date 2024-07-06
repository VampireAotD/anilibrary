<?php

declare(strict_types=1);

namespace Tests\Feature\Services\Scraper;

use App\Services\Scraper\Client;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Http\Client\Request;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class ClientTest extends TestCase
{
    use WithFaker;

    protected Client $client;

    protected function setUp(): void
    {
        parent::setUp();

        $this->client = $this->app->make(Client::class);
    }

    public function testServiceWillThrowRequestExceptionOnFailure(): void
    {
        Http::fake(function () {
            return Http::response(['error' => 'Invalid URL'], Response::HTTP_NOT_FOUND);
        });

        $this->expectException(RequestException::class);
        $this->client->scrapeByUrl($this->faker->url);
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
            $this->assertEquals(now()->addDays(14)->unix(), $decodedToken['exp']);

            return Http::response(['data' => 'test data']);
        });

        $response = $this->client->scrapeByUrl($this->faker->url);

        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertEquals(['data' => 'test data'], $response->json());
    }

    public function testServiceCanSendScrapeRequest(): void
    {
        Http::fake(function (Request $request) {
            $this->assertEquals(config('services.scraper.url') . Client::SCRAPE_ENDPOINT, $request->url());
            $this->assertNotEmpty($request->headers()['Authorization']);
            $this->assertArrayHasKey('url', $request->data());

            return Http::response(['data' => 'test data']);
        });

        $response = $this->client->scrapeByUrl($this->faker->url);

        $this->assertEquals(Response::HTTP_OK, $response->status());
        $this->assertEquals(['data' => 'test data'], $response->json());
    }
}
