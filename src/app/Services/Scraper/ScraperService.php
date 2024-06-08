<?php

declare(strict_types=1);

namespace App\Services\Scraper;

use Firebase\JWT\JWT;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\RequestException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;

final class ScraperService
{
    /**
     * @throws RequestException|ConnectionException
     */
    public function sendScrapeRequest(string $url): Response
    {
        $token = $this->createJWTToken();

        return Http::baseUrl(config('services.scraper.url'))
                   ->withToken($token)
                   ->post('/api/v1/anime/parse', ['url' => $url])
                   ->throw();
    }

    private function createJWTToken(): string
    {
        return JWT::encode([
            'iss' => 'anilibrary',
            'iat' => now()->unix(),
            'exp' => now()->addDays(14)->unix(),
        ], config('jwt.secret', ''), 'HS512');
    }
}
