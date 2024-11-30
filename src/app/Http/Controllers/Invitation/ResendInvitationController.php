<?php

declare(strict_types=1);

namespace App\Http\Controllers\Invitation;

use App\Http\Controllers\Controller;
use App\Models\Invitation;
use App\Services\Invitation\InvitationService;
use Illuminate\Http\Request;

final class ResendInvitationController extends Controller
{
    public function __construct(private readonly InvitationService $invitationService)
    {
    }

    /**
     * Handle the incoming request.
     */
    public function __invoke(Request $request, Invitation $invitation)
    {
        $this->invitationService->resend($invitation);

        return back()->with(['message' => __('invitation.sent')]);
    }
}
