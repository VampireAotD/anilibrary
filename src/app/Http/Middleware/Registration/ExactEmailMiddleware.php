<?php

declare(strict_types=1);

namespace App\Http\Middleware\Registration;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ExactEmailMiddleware
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
        $knownEmail = Cache::has(hash('sha256', $request->post('email', '')));

        abort_if(!$knownEmail, Response::HTTP_FORBIDDEN, 'Unknown email');

        return $next($request);
    }
}
