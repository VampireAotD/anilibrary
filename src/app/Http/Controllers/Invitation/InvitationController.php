<?php

declare(strict_types=1);

namespace App\Http\Controllers\Invitation;

use App\DTO\Service\Invitation\InvitationDTO;
use App\Enums\Invitation\StatusEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\Invitation\SendInvitationRequest;
use App\Models\Invitation;
use App\Services\Invitation\InvitationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class InvitationController extends Controller
{
    public function __construct(
        private readonly InvitationService $invitationService
    ) {
    }

    /**
     * Display a listing of the resource.
     */
    public function index(): Response
    {
        $invitations = $this->invitationService->paginate();

        return Inertia::render('Invitation/Index', compact('invitations'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SendInvitationRequest $request): RedirectResponse
    {
        try {
            $this->invitationService->createAndAccept(
                new InvitationDTO($request->input('email'), StatusEnum::ACCEPTED)
            );

            return back()->with(['message' => __('invitation.accepted')]);
        } catch (Throwable $throwable) {
            Log::error('Error sending invitation', [
                'exception_message' => $throwable->getMessage(),
                'exception_trace'   => $throwable->getTraceAsString(),
            ]);

            return back()->with(['message' => __('invitation.failed_to_create')]);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Invitation $invitation): RedirectResponse
    {
        $this->invitationService->accept($invitation);

        return back()->with(['message' => __('invitation.accepted')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invitation $invitation): RedirectResponse
    {
        $this->invitationService->decline($invitation);

        return back()->with(['message' => __('invitation.declined')]);
    }
}
