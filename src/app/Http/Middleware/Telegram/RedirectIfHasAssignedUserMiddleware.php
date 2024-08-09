<?php

declare(strict_types=1);

namespace App\Http\Middleware\Telegram;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RedirectIfHasAssignedUserMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Request                       $request
     * @param \Closure(Request): (Response) $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        if ($request->user()->telegramUser) {
            return back()->withErrors(['message' => 'You already have assigned Telegram account']);
        }

        return $next($request);
    }
}
