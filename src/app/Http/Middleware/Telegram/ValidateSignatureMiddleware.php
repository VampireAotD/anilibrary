<?php

declare(strict_types=1);

namespace App\Http\Middleware\Telegram;

use App\Services\TelegramUserService;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

readonly class ValidateSignatureMiddleware
{
    public function __construct(private TelegramUserService $telegramUserService)
    {
    }

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

        $signature = $this->telegramUserService->generateSignature($request->toArray());
        abort_if(!hash_equals($telegramHash, $signature), Response::HTTP_FORBIDDEN);

        return $next($request);
    }
}
