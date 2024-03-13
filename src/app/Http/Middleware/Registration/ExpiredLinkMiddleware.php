<?php

declare(strict_types=1);

namespace App\Http\Middleware\Registration;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use Symfony\Component\HttpFoundation\Response;

class ExpiredLinkMiddleware
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
        $canRegister = Cache::has($request->get('hash', ''));

        abort_if(!$canRegister, Response::HTTP_FORBIDDEN, 'Invalid signature');

        return $next($request);
    }
}
