<?php

declare(strict_types=1);

namespace App\Http\Middleware\Registration;

use App\Enums\Invitation\StatusEnum;
use App\Models\Invitation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class HasInvitationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $invitation = Invitation::query()->where([
            'id'     => $request->get('invitationId'),
            'status' => StatusEnum::ACCEPTED,
        ])->first();

        abort_if(!$invitation, Response::HTTP_FORBIDDEN, __('auth.middleware.invalid_invitation'));

        $request->merge(['invitation' => $invitation]);

        return $next($request);
    }
}
