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
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Inertia\Response;
use Throwable;

class InvitationController extends Controller implements HasMiddleware
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
            $this->invitationService->createAndSend(
                new InvitationDTO($request->input('email'), StatusEnum::ACCEPTED)
            );

            return back()->with(['message' => 'Invitation sent']);
        } catch (Throwable $exception) {
            Log::error('Error sending invitation', [
                'exception_message' => $exception->getMessage(),
                'exception_trace'   => $exception->getTraceAsString(),
            ]);

            return back()->with(['message' => 'Could not create and sent invitation']);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Invitation $invitation)
    {
        $this->invitationService->send($invitation);

        return back()->with(['message' => 'Invitation accepted']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invitation $invitation)
    {
        $this->invitationService->decline($invitation);

        return back()->with(['message' => 'Invitation declined']);
    }

    /**
     * Get the middleware that should be assigned to the controller.
     *
     * @return Middleware[]
     */
    public static function middleware(): array
    {
        return [
            new Middleware('invitation.is_pending', only: ['update']),
            new Middleware('invitation.not_declined', only: ['destroy']),
        ];
    }
}
