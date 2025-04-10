<?php

declare(strict_types=1);

namespace App\Http\Middleware\Invitation;

use App\Enums\Invitation\StatusEnum;
use App\Models\Invitation;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

final class NotDeclinedInvitationMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        /** @var Invitation $invitation */
        $invitation = $request->route('invitation');

        abort_if(
            $invitation->status === StatusEnum::DECLINED,
            Response::HTTP_BAD_REQUEST,
            __('invitation.already_declined')
        );

        return $next($request);
    }
}
