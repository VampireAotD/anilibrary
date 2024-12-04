<?php

declare(strict_types=1);

namespace App\Http\Middleware\Invitation;

use App\Enums\Invitation\StatusEnum;
use App\Models\Invitation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class InvitationHasStatusMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next, string $status): Response
    {
        /** @var Invitation $invitation */
        $invitation = $request->route('invitation');

        abort_if(
            $invitation->status !== StatusEnum::tryFrom($status),
            Response::HTTP_FORBIDDEN,
            __('invitation.invalid_status')
        );

        return $next($request);
    }
}
