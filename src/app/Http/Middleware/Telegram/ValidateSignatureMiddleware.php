<?php

declare(strict_types=1);

namespace App\Http\Middleware\Telegram;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ValidateSignatureMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request                      $request
     * @param Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        $telegramHash = $request->get('hash');
        abort_if(!$telegramHash, Response::HTTP_BAD_REQUEST, 'Missing Telegram signature');

        // For the time being, Anilibrary has only one bot and not planning to add another one,
        // so we can just take token from its bot
        $hashedToken = hash('sha256', config('telebot.bots.anilibrary.token'), true);

        // Transform all request fields into signature
        $signature = $request->collect()
                             ->except('hash')
                             ->map(fn(mixed $value, string $key) => sprintf('%s=%s', $key, $value))
                             ->values()
                             ->sort()
                             ->implode(PHP_EOL);

        $hashedSignature = hash_hmac('sha256', $signature, $hashedToken);

        abort_if(!hash_equals($telegramHash, $hashedSignature), Response::HTTP_FORBIDDEN);

        return $next($request);
    }
}
