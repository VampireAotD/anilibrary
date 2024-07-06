<?php

declare(strict_types=1);

namespace App\Services\Scraper;

use Firebase\JWT\JWT;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final readonly class Client
{
    public const string SCRAPE_ENDPOINT = '/api/v1/anime/scrape';

    /**
     * @throws RequestException|ConnectionException
     */
    public function scrapeByUrl(string $url): Response
    {
        return Http::baseUrl(config('services.scraper.url'))
                   ->withToken($this->createToken())
                   ->post(self::SCRAPE_ENDPOINT, ['url' => $url])
                   ->throw();
    }

    private function createToken(): string
    {
        return JWT::encode([
            'iss' => 'anilibrary',
            'iat' => now()->unix(),
            'exp' => now()->addDays(14)->unix(),
        ], config('jwt.secret', ''), 'HS512');
    }
}
